<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator;

abstract class SecurityHeader
{
    /** @var string */
    private $value;
    /** @var string[] */
    private $recommendations = [];

    abstract public function getScore(): float;

    public function getRecommendations(): array
    {
        return $this->recommendations;
    }

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    protected function addRecommendation(string $recommendation): void
    {
        $this->recommendations[] = $recommendation;
    }

    protected function getValue(): string
    {
        return trim($this->value);
    }
}
