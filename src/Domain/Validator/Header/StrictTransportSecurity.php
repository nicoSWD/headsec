<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class StrictTransportSecurity extends SecurityHeader
{
    public function getScore(): float
    {
        $value = $this->getValue();
        $score = 0;

        if (preg_match('~max-age=(\d+)~', strtolower($value), $maxAge)) {
            $maxAge = (int) $maxAge[1];

            if ($maxAge >= 15768000) {
                $score = 1;
            } else {
                $score = .5;
                $this->addRecommendation('max-age should be at least 6 months (15768000 seconds)');
            }
        }

        if (strpos($value, 'includeSubdomains') === false) {
            $this->addRecommendation('The flag includeSubdomains should be set');
        }

        return $score;
    }
}
