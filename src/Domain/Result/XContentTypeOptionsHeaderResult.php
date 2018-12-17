<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class XContentTypeOptionsHeaderResult extends AbstractHeaderAuditResult
{
    private $isNoSniff = false;

    public function isSecure(): bool
    {
        return $this->isNoSniff();
    }

    public function setIsNoSniff(bool $isNoSniff): void
    {
        $this->isNoSniff = $isNoSniff;
    }

    public function isNoSniff(): bool
    {
        return $this->isNoSniff;
    }
}
