<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

abstract class SecurityHeader
{
    /** @var string|string[] */
    private $value;
    /** @var string[] */
    private $warnings = [];

    /** @throws \nicoSWD\SecHeaderCheck\Domain\Validator\Exception\DuplicateHeaderException */
    abstract public function getScore(): float;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    protected function addWarning(string $warning): void
    {
        $this->warnings[] = $warning;
    }

    protected function getValue()
    {
        if (is_array($this->value)) {
            return array_map('trim', $this->value);
        }

        return trim($this->value);
    }

    protected function getUniqueValue(): string
    {
        if (is_array($this->value)) {
            throw new Exception\DuplicateHeaderException();
        }

        return $this->value;
    }
}
