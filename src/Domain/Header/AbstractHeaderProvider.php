<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\Exception\TooManyRedirectsException;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;

abstract class AbstractHeaderProvider implements HeaderProviderInterface
{
    /** @var int */
    private $maxRedirects;
    /** @var int */
    protected $connectionTimeout;

    public function __construct(int $maxRedirects, int $connectionTimeout)
    {
        $this->maxRedirects = $maxRedirects;
        $this->connectionTimeout = $connectionTimeout;
    }

    abstract public function getRawHeaders(URL $url): string;

    public function getHeadersFromUrl(URL $url, bool $followRedirects = true): HttpHeaderBag
    {
        $headers = $this->getHeadersFromString($this->getRawHeaders($url));

        if ($followRedirects) {
            $headers = $this->getRawHeadersFromRedirectingUrl($url, $headers);
        }

        return $headers;
    }

    public function getHeadersFromString(string $headers): HttpHeaderBag
    {
        $headersLines = preg_split('~\r?\n~', $headers, -1, PREG_SPLIT_NO_EMPTY);
        $parsedHeaders = [];

        foreach ($headersLines as $line) {
            [$headerName, $headerValue] = $this->getNameAndValue($line);
            $parsedHeaders[$headerName][] = $headerValue;
        }

        return $this->headerArrayToBag($parsedHeaders);
    }

    private function headerArrayToBag(array $headers): HttpHeaderBag
    {
        $headerBag = new HttpHeaderBag();

        foreach ($headers as $headerName => $values) {
            foreach ($values as $value) {
                $headerBag->add($this->createHeader($headerName, $value));
            }
        }

        return $headerBag;
    }

    private function getRawHeadersFromRedirectingUrl(URL $url, HttpHeaderBag $headers): HttpHeaderBag
    {
        $numRedirects = 0;

        while ($headers->has('location')) {
            if (++$numRedirects > $this->maxRedirects) {
                throw new TooManyRedirectsException();
            }

            $headers = $this->getHeadersFromString(
                $this->getRawHeaders($url->redirectTo($headers->get('location')[0]))
            );
        }

        return $headers;
    }

    protected function getNameAndValue(string $line): array
    {
        $parts = explode(':', $line, 2);
        $headerName = trim(strtolower($parts[0]));
        $headerValue = trim($parts[1] ?? '');

        return [$headerName, $headerValue];
    }

    private function createHeader(string $headerName, string $value): HttpHeader
    {
        return new HttpHeader(strtolower(trim($headerName)), $value);
    }
}
