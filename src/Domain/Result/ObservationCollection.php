<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use Iterator;
use SplObjectStorage;

final class ObservationCollection implements Iterator
{
    private $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function addWarning(Warning $warning): void
    {
        $this->storage->attach($warning);
    }

    public function addKudos(Kudos $kudos): void
    {
        $this->storage->attach($kudos);
    }

    public function addInfo(Info $info): void
    {
        $this->storage->attach($info);
    }

    public function addError(Error $error): void
    {
        $this->storage->attach($error);
    }

    public function empty(): bool
    {
        return $this->storage->count() === 0;
    }

    public function current()
    {
        return $this->storage->current();
    }

    public function next()
    {
        $this->storage->next();
    }

    public function key()
    {
        return $this->storage->key();
    }

    public function valid()
    {
        return $this->storage->valid();
    }

    public function rewind()
    {
        $this->storage->rewind();
    }
}
