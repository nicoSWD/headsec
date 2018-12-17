<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithInsufficientMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class StrictTransportSecurityHeader extends AbstractHeaderValidator
{
    private const SIX_MONTHS_IN_SECONDS = 15768000;
    private const FLAG_INCLUDE_SUB_DOMAINS = 'includesubdomains';

    protected function scan(): void
    {
        $value = $this->getValue();
        $maxAge = $this->getMaxAge($value);

        if ($maxAge !== false) {
            if (!$this->isMinRecommendedMaxAge($maxAge)) {
                $this->addWarning(new StrictTransportSecurityWithInsufficientMaxAgeWarning((string) $maxAge));
            }
        } else {
            $this->addWarning(new StrictTransportSecurityWithMissingMaxAgeWarning());
        }

        if (!$this->hasIncludeSubDomainsFlag($value)) {
            $this->addWarning(new StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning());
        }
    }

    private function hasIncludeSubDomainsFlag(string $value): bool
    {
        $callback = function (string $option): string {
            return strtolower(trim($option));
        };

        $options = array_map($callback, explode(';', $value));

        return in_array(self::FLAG_INCLUDE_SUB_DOMAINS, $options, true);
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
