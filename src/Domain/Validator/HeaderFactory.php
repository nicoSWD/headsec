<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Headers\SecurityHeaders;

final class HeaderFactory
{
    public function createFromHeader(string $header, $value): SecurityHeader
    {
        switch ($header) {
            case SecurityHeaders::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurity($value);
            case SecurityHeaders::X_FRAME_OPTIONS:
                return new Header\XFrameOptions($value);
            case SecurityHeaders::X_XSS_PROTECTION:
                return new Header\XXSSProtection($value);
            case SecurityHeaders::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptions($value);
            case SecurityHeaders::REFERRER_POLICY:
                return new Header\ReferrerPolicy($value);
            case SecurityHeaders::SET_COOKIE:
                return new Header\SetCookie($value);
            default:
                return new Header\NonSecurityHeader($value);
        }
    }
}
