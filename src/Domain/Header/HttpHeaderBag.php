<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use Iterator;

final class HttpHeaderBag implements Iterator
{
    /** @var HttpHeader[] */
    private $headers = [];

    public function add(HttpHeader $headerValue)
    {
        $this->headers[] = $headerValue;
    }

    public function has(string $headerName): bool
    {
        foreach ($this->headers as $header) {
            if ($header->name() === $headerName) {
                return true;
            }
        }

        return false;
    }

    public function get(string $headerName): array
    {
        $headers = [];

        foreach ($this->headers as $header) {
            if ($header->name() === $headerName) {
                $headers[] = $header;
            }
        }

        return $headers;
    }

    /** @return HttpHeader|bool */
    public function current()
    {
        return current($this->headers);
    }

    public function next()
    {
        return next($this->headers);
    }

    public function key()
    {
        return key($this->headers);
    }

    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->headers);
    }
}
