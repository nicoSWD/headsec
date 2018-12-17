<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class SetCookieResult extends AbstractHeaderAuditResult
{
    private $hasFlagSecure = false;
    private $hasFlagHttpOnly = false;
    private $hasFlagSameSite = false;

    public function isSecure(): bool
    {
        return $this->hasFlagHttpOnly() && $this->hasFlagSecure();
    }

    public function hasFlagSecure(): bool
    {
        return $this->hasFlagSecure;
    }

    public function setHasFlagSecure(bool $hasFlagSecure): void
    {
        $this->hasFlagSecure = $hasFlagSecure;
    }

    public function hasFlagHttpOnly(): bool
    {
        return $this->hasFlagHttpOnly;
    }

    public function setHasFlagHttpOnly(bool $hasFlagHttpOnly): void
    {
        $this->hasFlagHttpOnly = $hasFlagHttpOnly;
    }

    public function hasFlagSameSite(): bool
    {
        return $this->hasFlagSameSite;
    }

    public function setHasFlagSameSite(bool $hasFlagSameSite): void
    {
        $this->hasFlagSameSite = $hasFlagSameSite;
    }
}
