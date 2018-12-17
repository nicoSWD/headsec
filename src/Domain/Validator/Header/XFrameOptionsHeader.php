<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\XFrameOptionsResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XFrameOptionsHeader extends AbstractHeaderValidator
{
    private const OPTION_DENY = 'deny';
    private const OPTION_SAME_ORIGIN = 'sameorigin';

    public function audit(): AbstractHeaderAuditResult
    {
        $XFrameOptionsResult = new XFrameOptionsResult($this->getName());
        $XFrameOptionsResult->setHasSecureOrigin($this->isSecureOrigin());
        $XFrameOptionsResult->setHasAllowFrom($this->hasAllowFrom());

        return $XFrameOptionsResult;
    }

    private function isSecureOrigin(): bool
    {
        $value = strtolower($this->getValue());

        return $value === self::OPTION_DENY || $value === self::OPTION_SAME_ORIGIN;
    }

    private function hasAllowFrom(): bool
    {
        return strpos(strtolower($this->getValue()), 'allow-from ') === 0;
    }
}
