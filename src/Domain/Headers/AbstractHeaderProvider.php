<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Headers;

abstract class AbstractHeaderProvider
{
    abstract protected function getHeaders(string $url): array;

    public function getHeadersFromUrl(string $url): array
    {
        if (!$this->isValidUrl($url)) {
            throw new \Exception('Invalid URL');
        }

        $headers = $this->getHeaders($url);
        $headers = array_change_key_case($headers, CASE_LOWER);
        unset($headers[0]);

        return $headers;
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

        return in_array($scheme, ['http', 'https'], true);
    }
}