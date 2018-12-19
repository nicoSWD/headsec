<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class XFrameOptionsResult extends AbstractParsedHeader
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

    public function setHasSecureOrigin(bool $hasSecureOrigin): self
    {
        $this->hasSecureOrigin = $hasSecureOrigin;

        return $this;
    }

    public function hasAllowFrom(): bool
    {
        return $this->hasAllowFrom;
    }

    public function setHasAllowFrom(bool $hasAllowFrom): self
    {
        $this->hasAllowFrom = $hasAllowFrom;

        return $this;
    }
}
