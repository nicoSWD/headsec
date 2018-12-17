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
    private $hasFlagIncludeSubdomains = false;

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

    public function hasFlagIncludeSubdomains(): bool
    {
        return $this->hasFlagIncludeSubdomains;
    }

    public function setHasFlagIncludeSubdomains(bool $hasFlagIncludeSubdomains): void
    {
        $this->hasFlagIncludeSubdomains = $hasFlagIncludeSubdomains;
    }
}
