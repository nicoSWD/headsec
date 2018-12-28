<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Result\NonSecurityHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class NonSecurityHeader extends AbstractHeaderParser
{
    public function parse(): NonSecurityHeaderResult
    {
        return new NonSecurityHeaderResult($this->getName(), $this->getValue());
    }
}
