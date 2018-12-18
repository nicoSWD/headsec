<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class StrictTransportSecurityHeaderResult extends AbstractHeaderAuditResult
{
    private $hasSecureMaxAge = false;
    private $hasFlagIncludeSubDomains = false;

    public function isSecure(): bool
    {
        return $this->hasSecureMaxAge();
    }

    public function hasSecureMaxAge(): bool
    {
        return $this->hasSecureMaxAge;
    }

    public function setHasSecureMaxAge(bool $hasSecureMaxAge): void
    {
        $this->hasSecureMaxAge = $hasSecureMaxAge;
    }

    public function hasFlagIncludeSubDomains(): bool
    {
        return $this->hasFlagIncludeSubDomains;
    }

    public function setHasFlagIncludeSubDomains(bool $hasFlagIncludeSubDomains): void
    {
        $this->hasFlagIncludeSubDomains = $hasFlagIncludeSubDomains;
    }
}
