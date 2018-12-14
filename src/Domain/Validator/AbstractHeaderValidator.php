<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Result\EvaluatedHeader;

abstract class AbstractHeaderValidator
{
    private $name = '';
    private $value = '';
    private $warnings = [];
    private $penalty = .0;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    abstract protected function scan(): void;

    final public function getEvaluatedHeader(): EvaluatedHeader
    {
        $this->scan();

        return new EvaluatedHeader(
            $this->getName(),
            $this->getValue(),
            $this->getScore(),
            $this->getWarnings()
        );
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getValue(): string
    {
        return trim($this->value);
    }

    protected function addWarning(float $penalty, string $warning, array $context = []): void
    {
        $this->warnings[] = vsprintf($warning, $context);
        $this->penalty += $penalty;
    }

    private function getScore(): float
    {
        return 1 - $this->getPenalty();
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
