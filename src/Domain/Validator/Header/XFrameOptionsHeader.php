<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XFrameOptionsHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class XFrameOptionsHeader extends AbstractHeaderParser
{
    private const OPTION_DENY = 'deny';
    private const OPTION_SAME_ORIGIN = 'sameorigin';

    public function parse(): AbstractParsedHeader
    {
        return (new XFrameOptionsHeaderResult($this->getName(), $this->getValue()))
            ->setHasSecureOrigin($this->isSecureOrigin())
            ->setHasAllowFrom($this->hasAllowFrom());
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
