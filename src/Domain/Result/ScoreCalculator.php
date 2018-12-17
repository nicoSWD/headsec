<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ScoreCalculator
{
    public function calculateScore(UnprocessedAuditionResult $scanResult)
    {
        $possibleScore = 0;
        $penalties = 0;

        foreach ($scanResult->getHeaders() as $header) {
            $possibleScore++;

            if (!$header->isSecure()) {
                $penalties += 0.3;
            }
        }

        return ($possibleScore - $penalties);
    }
}
