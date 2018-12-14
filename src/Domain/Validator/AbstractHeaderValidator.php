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

    abstract protected function scan(): void;

    final public function getEvaluatedHeader(): EvaluatedHeader
    {
        $this->scan();

        $score = 1 - $this->getPenalty();

        return new EvaluatedHeader($this->getName(), $this->getValue(), $score, $this->getWarnings());
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

    private function getPenalty(): float
    {
        return $this->penalty;
    }

    private function getName(): string
    {
        return $this->name;
    }
}
