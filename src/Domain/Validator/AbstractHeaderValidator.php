<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\EvaluatedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\Warning;

abstract class AbstractHeaderValidator
{
    private $name = '';
    private $value = '';
    private $warnings = [];
    private $penalty = .0;

    public function __construct(HttpHeader $header)
    {
        $this->name = $header->name();
        $this->value = $header->value();
    }

    abstract protected function scan(): void;

    final public function auditHeader(): EvaluatedHeader
    {
        $this->scan();

        return new EvaluatedHeader(
            $this->getName(),
            $this->getValue(),
            $this->getScore(),
            $this->getWarnings()
        );
    }

    public function getValue(): string
    {
        return trim($this->value);
    }

    protected function addWarning(Warning $warning): void
    {
        $this->warnings[] = $warning;
    }

    private function getWarnings(): array
    {
        return $this->warnings;
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
