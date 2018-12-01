<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XFrameOptionsHeader extends AbstractHeaderValidator
{
    private const OPTION_DENY = 'deny';
    private const OPTION_SAME_ORIGIN = 'sameorigin';

    public function getScore(): float
    {
        $value = strtolower($this->getUniqueValue());

        if ($this->isSecureOrigin($value) || $this->hasAllowFrom($value)) {
            return 1;
        }

        return .0;
    }

    private function isSecureOrigin(string $value): bool
    {
        return $value === self::OPTION_DENY || $value === self::OPTION_SAME_ORIGIN;
    }

    private function hasAllowFrom(string $value): bool
    {
        return strpos($value, 'allow-from ') === 0;
    }
}
