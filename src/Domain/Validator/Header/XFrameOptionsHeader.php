<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XFrameOptionsWithInsecureValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XFrameOptionsHeader extends AbstractHeaderValidator
{
    private const OPTION_DENY = 'deny';
    private const OPTION_SAME_ORIGIN = 'sameorigin';

    protected function scan(): void
    {
        if (!$this->isSecureOrigin() && !$this->hasAllowFrom()) {
            $this->addWarning(new XFrameOptionsWithInsecureValueWarning());
        }
    }

    private function isSecureOrigin(): bool
    {
        $value = strtolower($this->getValue());

        return $value === self::OPTION_DENY || $value === self::OPTION_SAME_ORIGIN;
    }

    private function hasAllowFrom(): bool
    {
        $value = strtolower($this->getValue());

        return strpos($value, 'allow-from ') === 0;
    }
}
