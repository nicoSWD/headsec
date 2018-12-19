<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Result;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

final class XPoweredByHeaderResult extends AbstractParsedHeader
{
    public function isSecure(): bool
    {
        return false;
    }
}
