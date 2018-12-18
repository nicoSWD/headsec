<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class XXSSProtectionHeaderResult extends AbstractHeaderAuditResult
{
    private $protectionIsOn = false;
    private $isBlocking = false;
    private $hasReportUri = false;

    public function isSecure(): bool
    {
        return $this->protectionIsOn() && $this->isBlocking();
    }

    public function protectionIsOn(): bool
    {
        return $this->protectionIsOn;
    }

    public function setProtectionIsOn(bool $protectionIsOn): self
    {
        $this->protectionIsOn = $protectionIsOn;

        return $this;
    }

    public function isBlocking(): bool
    {
        return $this->isBlocking;
    }

    public function setIsBlocking(bool $isBlocking): self
    {
        $this->isBlocking = $isBlocking;

        return $this;
    }

    public function hasReportUri(): bool
    {
        return $this->hasReportUri;
    }

    public function setHasReportUri(bool $hasReportUri): self
    {
        $this->hasReportUri = $hasReportUri;

        return $this;
    }
}
