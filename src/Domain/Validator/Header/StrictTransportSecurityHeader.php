<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class StrictTransportSecurityHeader extends AbstractHeaderValidator
{
    private const SIX_MONTHS_IN_SECONDS = 15768000;

    public function getScore(): float
    {
        $value = $this->getUniqueValue();
        $maxAge = $this->getMaxAge($value);

        if ($maxAge !== false) {
            if ($this->isMinRecommendedMaxAge($maxAge)) {
                $score = 1;
            } else {
                $score = .5;
                $this->addWarning('max-age should be at least 6 months (15768000 seconds)');
            }
        } else {
            $score = 0;
            $this->addWarning('Missing or invalid max-age');
        }

        if (!$this->hasIncludeSubDomainsFlag($value)) {
            $this->addWarning('The flag includeSubdomains should be set');
        }

        return $score;
    }

    private function hasIncludeSubDomainsFlag(string $value): bool
    {
        $options = explode(';', $value);
        $options = array_map('trim', $options);
        $options = array_map('strtolower', $options);

        return in_array('includesubdomains', $options, true);
    }

    private function getMaxAge(string $value)
    {
        if (preg_match('~max-age\s*=\s*([1-9]\d*)~i', $value, $maxAge)) {
            return (int) $maxAge[1];
        }

        return false;
    }

    private function isMinRecommendedMaxAge($maxAge): bool
    {
        return $maxAge >= self::SIX_MONTHS_IN_SECONDS;
    }
}
