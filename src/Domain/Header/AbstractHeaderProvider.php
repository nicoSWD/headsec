<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\Exception\TooManyRedirectsException;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;

abstract class AbstractHeaderProvider
{
    /** @var int */
    private $maxRedirects;
    /** @var int */
    protected $connectionTimeout;

    abstract protected function getRawHeaders(URL $url): array;

    public function __construct(int $maxRedirects, int $connectionTimeout)
    {
        $this->maxRedirects = $maxRedirects;
        $this->connectionTimeout = $connectionTimeout;
    }

    public function getHeadersFromUrl(URL $url, bool $followRedirects = true): HeaderBag
    {
        $headers = $this->getRawHeaders($url);

        if ($followRedirects) {
            $headers = $this->getRawHeadersFromRedirectingUrl($url, $headers);
        }

        $headerBag = new HeaderBag();

        foreach ($headers as $headerName => $value) {
            $headerBag[$headerName] = $this->createHeader($headerName, $value);
        }

        return $headerBag;
    }

    private function createHeader($headerName, $value): HttpHeader
    {
        if (is_int($headerName)) {
            return new HttpHeader(trim($value), '');
        }

        return new HttpHeader(strtolower(trim($headerName)), $value);
    }

    private function getRawHeadersFromRedirectingUrl(URL $url, array $headers): array
    {
        $numRedirects = 0;

        while (!empty($headers['location'])) {
            if (++$numRedirects > $this->maxRedirects) {
                throw new TooManyRedirectsException();
            }

            $headers = $this->getRawHeaders($url->redirectTo($headers['location']));
        }

        return $headers;
    }
}
