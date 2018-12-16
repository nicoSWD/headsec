<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Warning;

abstract class Warning
{
    private $context = [];
    private $penalty = .0;
    protected $message = '';

    public function __construct(?string ...$context)
    {
        $this->context = $context;
    }

    public function getPenalty(): float
    {
        return $this->penalty;
    }

    public function getMessage(): string
    {
        return vsprintf($this->message, $this->context);
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
