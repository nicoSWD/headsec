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

    abstract public function getRawHeaders(URL $url): array;

    public function getHeadersFromUrl(URL $url, bool $followRedirects = true): HttpHeaderBag
    {
        $headers = $this->getRawHeaders($url);

        if ($followRedirects) {
            $headers = $this->getRawHeadersFromRedirectingUrl($url, $headers);
        }

        $headerBag = new HttpHeaderBag();

        foreach ($headers as $headerName => $values) {
            foreach ($values as $value) {
                $headerBag->add($this->createHeader($headerName, $value));
            }
        }

        return $headerBag;
    }

    private function getRawHeadersFromRedirectingUrl(URL $url, array $headers): array
    {
        $numRedirects = 0;

        while (!empty($headers['location'][0])) {
            if (++$numRedirects > $this->maxRedirects) {
                throw new TooManyRedirectsException();
            }

            $headers = $this->getRawHeaders($url->redirectTo($headers['location'][0]));
        }

        return $headers;
    }

    private function createHeader(string $headerName, string $value): HttpHeader
    {
        return new HttpHeader(strtolower(trim($headerName)), $value);
    }
}
