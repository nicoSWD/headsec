<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Result\EvaluatedHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Exception\DuplicateHeaderException;

abstract class AbstractHeaderValidator
{
    /** @var string|string[] */
    private $value;
    private $warnings = [];
    private $penalty = .0;
    private $name = '';

    public function __construct(string $name, $value = '')
    {
        $this->name = $name;
        $this->value = $value;
    }

    /** @throws DuplicateHeaderException */
    abstract protected function scan(): void;

    final public function getEvaluatedHeader(): EvaluatedHeader
    {
        try {
            $this->scan();
        } catch (DuplicateHeaderException $e) {
            $this->addWarning(1, ValidationError::HEADER_DUPLICATE);
        }

        $score = 1 - $this->getPenalty();

        return new EvaluatedHeader($this->getName(), $score, $this->getWarnings(), (array) $this->getValue());
    }

    public function getWarnings(): array
    {
        return array_unique($this->warnings);
    }

    public function getValue()
    {
        if (is_array($this->value)) {
            return array_map('trim', $this->value);
        }

        return trim($this->value);
    }

    protected function addWarning(float $penalty, string $warning, array $context = []): void
    {
        $this->warnings[] = vsprintf($warning, $context);
        $this->penalty += $penalty;
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

    private function getName(): string
    {
        return $this->name;
    }
}
