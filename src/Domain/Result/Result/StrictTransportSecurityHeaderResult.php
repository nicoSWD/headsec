<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Result;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

final class StrictTransportSecurityHeaderResult extends AbstractParsedHeader
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

    public function setHasSecureMaxAge(bool $hasSecureMaxAge): self
    {
        $this->hasSecureMaxAge = $hasSecureMaxAge;

        return $this;
    }

    public function hasFlagIncludeSubDomains(): bool
    {
        return $this->hasFlagIncludeSubDomains;
    }

    public function setHasFlagIncludeSubDomains(bool $hasFlagIncludeSubDomains): self
    {
        $this->hasFlagIncludeSubDomains = $hasFlagIncludeSubDomains;

        return $this;
    }
}
