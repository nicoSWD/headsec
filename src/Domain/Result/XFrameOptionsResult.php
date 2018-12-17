<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class XFrameOptionsResult extends AbstractHeaderAuditResult
{
    private $hasSecureOrigin = false;
    private $hasAllowFrom = false;

    public function isSecure(): bool
    {
        return $this->getHasSecureOrigin() || $this->hasAllowFrom();
    }

    public function getHasSecureOrigin(): bool
    {
        return $this->hasSecureOrigin;
    }

    public function setHasSecureOrigin(bool $hasSecureOrigin): void
    {
        $this->hasSecureOrigin = $hasSecureOrigin;
    }

    public function hasAllowFrom(): bool
    {
        return $this->hasAllowFrom;
    }

    public function setHasAllowFrom(bool $hasAllowFrom): void
    {
        $this->hasAllowFrom = $hasAllowFrom;
    }
}
