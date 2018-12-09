<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Validator\Exception\DuplicateHeaderException;

abstract class AbstractHeaderValidator
{
    /** @var string|string[] */
    private $value;
    /** @var string[] */
    private $warnings = [];
    /** @var float */
    private $penalty = .0;

    public function __construct($value = '')
    {
        $this->value = $value;
    }

    /** @throws DuplicateHeaderException */
    abstract protected function scan(): void;

    final public function getCalculatedScore(): float
    {
        try {
            $this->scan();
        } catch (DuplicateHeaderException $e) {
            $this->addWarning(1, ValidationError::HEADER_DUPLICATE);
        }

        return 1 - $this->getPenalty();
    }

    public function getWarnings(): array
    {
        return array_unique($this->warnings);
    }

    protected function addWarning(float $penalty, string $warning, array $context = []): void
    {
        $this->warnings[] = vsprintf($warning, $context);
        $this->penalty += $penalty;
    }

    protected function getValue()
    {
        if (is_array($this->value)) {
            return array_map('trim', $this->value);
        }

        return trim($this->value);
    }

    /** @throws DuplicateHeaderException */
    protected function getUniqueValue(): string
    {
        if (is_array($this->value)) {
            throw new DuplicateHeaderException();
        }

        return trim($this->value);
    }

    private function getPenalty(): float
    {
        return $this->penalty;
    }
}
