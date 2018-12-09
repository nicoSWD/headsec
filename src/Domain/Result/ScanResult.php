<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ScanResult
{
    /** @var float[] */
    private $score = [];
    /** @var array */
    private $warnings = [];

    public function addWarnings(string $headerName, array $warnings): void
    {
        $this->warnings[$headerName] = $warnings;
    }

    public function sumScore(string $headerName, float $score): void
    {
        if (!isset($this->score[$headerName])) {
            $this->score[$headerName] = 0;
        }

        $this->score[$headerName] += $score;
    }

    public function getScore(): float
    {
        return array_sum($this->score);
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getSortedWarnings(): array
    {
        uasort($this->warnings, function ($a, $b) {
            return -(count($a) <=> count($b));
        });

        return $this->warnings;
    }
}
