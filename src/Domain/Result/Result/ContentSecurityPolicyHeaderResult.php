<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Result;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

final class ContentSecurityPolicyHeaderResult extends AbstractParsedHeader
{
    const LIE_ABOUT_RESULT = true;

    public function isSecure(): bool
    {
        return self::LIE_ABOUT_RESULT;
    }
}
