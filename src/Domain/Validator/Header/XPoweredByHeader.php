<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\XPoweredByHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class XPoweredByHeader extends AbstractHeaderParser
{
    public function parse(): AbstractParsedHeader
    {
        return new XPoweredByHeaderResult($this->getName(), $this->getValue());
    }
}
