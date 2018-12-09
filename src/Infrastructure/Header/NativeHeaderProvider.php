<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\AbstractHeaderProvider;
use nicoSWD\SecHeaderCheck\Domain\Header\Exception\InvalidUrlException;

final class NativeHeaderProvider extends AbstractHeaderProvider
{
    private const MAX_REDIRECTS = 5;
    private const ONE_KB = 1024;
    private const MAX_HEADER_SIZE = self::ONE_KB * 8;

    protected function getHeaders(string $url, bool $followRedirects, int $redirectCount = 0): array
    {
        if ($redirectCount > self::MAX_REDIRECTS) {
            throw new \Exception('Page redirected more than 5 times');
        }

        $components = parse_url($url);

        if ($components['scheme'] === 'https') {
            $port = 443;
            $scheme = 'ssl://';
        } else {
            $port = 80;
            $scheme = '';
        }

        $out = "GET / HTTP/1.1\r\n";
        $out .= "Host: {$components['host']}\r\n";
        $out .= "Accept: text/html\r\n";
        $out .= "User-Agent: Security Headers Scanner/1.0\r\n";
        $out .= "Connection: Close\r\n\r\n";

        $fp = @fsockopen($scheme . $components['host'], $port, $errNo, $errStr, 3);

        if (!$fp) {
            throw new \Exception("Unable to connect: {$errStr}");
        }

        fwrite($fp, $out);
        $headers = [];
        $bytesRead = 0;

        while (!feof($fp)) {
            $line = fgets($fp, self::ONE_KB * 3);
            $bytesRead += strlen($line);

            if ($bytesRead > self::MAX_HEADER_SIZE) {
                throw new \Exception();
            }

            if ($line === "\r\n") {
                break;
            }

            $parts = explode(':', trim($line), 2);
            $headerName = strtolower(trim($parts[0]));
            $headerValue = trim($parts[1] ?? '');

            if (!isset($headers[$headerName])) {
                $headers[$headerName] = $headerValue;
            } else {
                $headers[$headerName] = (array) $headers[$headerName];
                $headers[$headerName][] = $headerValue;
            }
        }

        fclose($fp);

        if ($followRedirects && !empty($headers['location'])) {
            $redirectUrl = $this->getRedirectUrl($components, $headers['location']);

            return $this->getHeaders($redirectUrl, $followRedirects, $redirectCount + 1);
        }

        return $headers;
    }

    private function getRedirectUrl(array $components, string $newLocation): string
    {
        $scheme = $components['scheme'];
        $host = $components['host'];
        $port = isset($components['port']) ? ":{$components['port']}" : '';

        if (preg_match('~^https?://~', $newLocation)) {
            if (!$this->isValidUrl($newLocation)) {
                throw new InvalidUrlException();
            }

            return $newLocation;
        }

        if (substr($newLocation, 0, 2) === '//') {
            return "{$scheme}:{$newLocation}";
        }

        if (substr($newLocation, 0, 1) === '/') {
            return "{$scheme}://{$host}{$port}{$newLocation}";
        }

        return "{$scheme}://{$host}{$port}{$components['path']}{$newLocation}";
    }
}
