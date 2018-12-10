<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use ArrayAccess;
use Iterator;

final class HeaderBag implements ArrayAccess, Iterator
{
    private $headers = [];

    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    public function has(string $headerName): bool
    {
        return array_key_exists($headerName, $this->headers);
    }

    public function get(string $headerName): HttpHeader
    {
        return $this->has($headerName) ? $this->headers[$headerName] : null;
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset): HttpHeader
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->headers[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->headers[$offset]);
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
