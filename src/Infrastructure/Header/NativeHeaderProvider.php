<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\AbstractHeaderProvider;
use nicoSWD\SecHeaderCheck\Domain\Header\Exception\ConnectionTimeoutException;
use nicoSWD\SecHeaderCheck\Domain\Header\Exception\MaxHeaderSizeExceededException;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;

final class NativeHeaderProvider extends AbstractHeaderProvider
{
    private const ONE_KB = 1024;
    private const MAX_HEADER_SIZE = self::ONE_KB * 8;

    public function getRawHeaders(URL $url): array
    {
        $fp = $this->connect($url);
        $this->sendRequest($url, $fp);

        $headers = [];
        $bytesRead = 0;

        while (!feof($fp)) {
            $line = $this->readLine($fp);

            if ($line === false || trim($line) === '') {
                break;
            }

            $bytesRead += strlen($line);

            if ($this->hasExceededMaxSize($bytesRead)) {
                throw new MaxHeaderSizeExceededException();
            }

            [$headerName, $headerValue] = $this->getNameAndValue($line);

            $headers[$headerName][] = $headerValue;
        }

        fclose($fp);

        return $headers;
    }

    private function getNameAndValue(string $line): array
    {
        $parts = explode(':', $line, 2);
        $headerName = trim(strtolower($parts[0]));
        $headerValue = trim($parts[1] ?? '');

        return [$headerName, $headerValue];
    }

    private function readLine($fp)
    {
        return fgets($fp, self::ONE_KB * 2);
    }

    private function hasExceededMaxSize(int $bytesRead): bool
    {
        return $bytesRead > self::MAX_HEADER_SIZE;
    }

    private function sendRequest(URL $url, $fp): void
    {
        $request = "HEAD {$url->path()}{$url->query()} HTTP/1.1\r\n";
        $request .= "Host: {$url->host()}\r\n";
        $request .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
        $request .= "User-Agent: Security Headers Scanner/1.0 (https://github.com/nicoSWD)\r\n";
        $request .= "Connection: Close\r\n\r\n";

        fwrite($fp, $request);
    }

    private function connect(URL $url)
    {
        if ($url->isHttps()) {
            $scheme = 'ssl://';
        } else {
            $scheme = '';
        }

        $fp = @fsockopen($scheme . $url->host(), $url->port(), $errNo, $errStr, $this->connectionTimeout);

        if (!$fp) {
            throw new ConnectionTimeoutException();
        }

        return $fp;
    }
}
