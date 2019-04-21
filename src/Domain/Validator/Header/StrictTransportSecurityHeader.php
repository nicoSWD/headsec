<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Result\StrictTransportSecurityHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class StrictTransportSecurityHeader extends AbstractHeaderParser
{
    private const ONE_YEAR_IN_SECONDS = 31536000;
    private const FLAG_INCLUDE_SUB_DOMAINS = 'includesubdomains';

    public function parse(): StrictTransportSecurityHeaderResult
    {
        return (new StrictTransportSecurityHeaderResult($this->getName(), $this->getValue()))
            ->setHasSecureMaxAge($this->isMinRecommendedMaxAge())
            ->setHasFlagIncludeSubDomains($this->hasIncludeSubDomainsFlag());
    }

    private function hasIncludeSubDomainsFlag(): bool
    {
        $callback = function (string $option): string {
            return strtolower(trim($option));
        };

        $options = array_map($callback, explode(';', $this->getValue()));

        return in_array(self::FLAG_INCLUDE_SUB_DOMAINS, $options, true);
    }

    private function isMinRecommendedMaxAge(): bool
    {
        $maxAge = $this->getMaxAge($this->getValue());

        return $maxAge !== false && $maxAge >= self::ONE_YEAR_IN_SECONDS;
    }

    private function getMaxAge(string $value)
    {
        if (preg_match('~max-age\s*=\s*([1-9]\d*)~i', $value, $maxAge)) {
            return (int) $maxAge[1];
        }

        return false;
    }
}
