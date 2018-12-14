<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use Iterator;

final class HeaderBag implements Iterator
{
    private $headers = [];

    public function add(HttpHeader $headerValue)
    {
        $this->headers[] = $headerValue;
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
