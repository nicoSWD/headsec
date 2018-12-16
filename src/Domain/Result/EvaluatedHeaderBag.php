<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use Iterator;

final class EvaluatedHeaderBag implements Iterator
{
    private $headers = [];

    public function add(EvaluatedHeader $header)
    {
        $this->headers[] = $header;
    }

    public function current()
    {
        return current($this->headers);
    }

    public function next()
    {
        next($this->headers);
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

    public function findMultiple(string $name)
    {
        $headers = [];

        foreach ($this->headers as $header) {
            if ($header->name() === $name) {
                $headers[] = $header;
            }
        }

        return $headers;
    }
}
