<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

abstract class AbstractObservation
{
    private $context = [];
    protected $message = '';

    abstract public function getPenalty(): float;

    public function __construct(?string ...$context)
    {
        $this->context = $context;
    }

    public function getMessage(): string
    {
        return vsprintf($this->message, $this->context);
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }

    public function isError(): bool
    {
        return false;
    }

    public function isWarning(): bool
    {
        return false;
    }

    public function isInfo(): bool
    {
        return false;
    }

    public function isKudos(): bool
    {
        return false;
    }
}
