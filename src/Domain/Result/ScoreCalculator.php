<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ScoreCalculator
{
    public function calculateScore(ScanResult $scanResult)
    {
        $possibleScore = 0;
        $penalties = 0;

        foreach ($scanResult->getHeaders() as $header) {
            $possibleScore++;

            if ($header->hasWarnings()) {
                $penalties += count($header->getEvaluatedHeader()->warnings()) / 10;
            }
        }

        return ($possibleScore - $penalties);
    }
}
