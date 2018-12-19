<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Result;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

final class SetCookieHeaderResult extends AbstractParsedHeader
{
    private $hasFlagSecure = false;
    private $hasFlagHttpOnly = false;
    private $hasFlagSameSite = false;
    private $cookieName = '';

    public function isSecure(): bool
    {
        return $this->hasFlagHttpOnly() && $this->hasFlagSecure();
    }

    public function hasFlagSecure(): bool
    {
        return $this->hasFlagSecure;
    }

    public function setHasFlagSecure(bool $hasFlagSecure): self
    {
        $this->hasFlagSecure = $hasFlagSecure;

        return $this;
    }

    public function hasFlagHttpOnly(): bool
    {
        return $this->hasFlagHttpOnly;
    }

    public function setHasFlagHttpOnly(bool $hasFlagHttpOnly): self
    {
        $this->hasFlagHttpOnly = $hasFlagHttpOnly;

        return $this;
    }

    public function hasFlagSameSite(): bool
    {
        return $this->hasFlagSameSite;
    }

    public function setHasFlagSameSite(bool $hasFlagSameSite): self
    {
        $this->hasFlagSameSite = $hasFlagSameSite;

        return $this;
    }

    public function setCookieName(string $cookieName): self
    {
        $this->cookieName = $cookieName;

        return $this;
    }
}
