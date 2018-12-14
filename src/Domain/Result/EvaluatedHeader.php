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
    private $value = '';
    private $score = .0;
    private $warnings = [];

    public function __construct(string $name, string $value, float $score, array $warnings)
    {
        $this->name = $name;
        $this->value = $value;
        $this->score = $score;
        $this->warnings = $warnings;
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
}
