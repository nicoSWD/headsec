<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class EvaluatedHeader
{
    private $name = '';
    private $score = .0;
    private $warnings = [];
    private $values = [];

    public function __construct(string $name, float $score, array $warnings, array $values)
    {
        $this->name = $name;
        $this->score = $score;
        $this->warnings = $warnings;
        $this->values = $values;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function score(): float
    {
        return $this->score;
    }

    public function warnings(): array
    {
        return $this->warnings;
    }

    public function values(): array
    {
        return $this->values;
    }
}
