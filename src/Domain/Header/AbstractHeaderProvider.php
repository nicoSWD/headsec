<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

abstract class AbstractHeaderProvider
{
    private const ALLOWED_PROTOCOLS = ['http', 'https'];

    abstract protected function getHeaders(string $url, bool $followRedirects): array;

    public function getHeadersFromUrl(string $url, bool $followRedirects): HeaderBag
    {
        if (!$this->isValidUrl($url)) {
            throw new Exception\InvalidUrlException();
        }

        $headerBag = new HeaderBag();

        foreach ($this->getHeaders($url, $followRedirects) as $headerName => $value) {
            $headerBag[$headerName] = $this->createHeader($headerName, $value);
        }

        return $headerBag;
    }

    protected function isValidUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if ($scheme === false || $scheme === null) {
            return false;
        }

        return in_array($scheme, self::ALLOWED_PROTOCOLS, true);
    }

    private function createHeader($headerName, $value): HttpHeader
    {
        if (is_int($headerName)) {
            return new HttpHeader(trim($value), '');
        }

        return new HttpHeader(strtolower(trim($headerName)), $value);
    }
}
