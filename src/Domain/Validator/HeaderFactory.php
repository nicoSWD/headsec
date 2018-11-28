<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

final class HeaderFactory
{
    private const STRICT_TRANSPORT_SECURITY = 'strict-transport-security';
    private const X_FRAME_OPTIONS = 'x-frame-options';
    private const X_XSS_PROTECTION = 'x-xss-protection';
    private const X_CONTENT_TYPE_OPTIONS = 'x-content-type-options';
    private const REFERRER_POLICY = 'referrer-policy';
    private const SET_COOKIE = 'set-cookie';

    public function createFromHeader(string $header, $value): SecurityHeader
    {
        switch ($header) {
            case self::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurity($value);
            case self::X_FRAME_OPTIONS:
                return new Header\XFrameOptions($value);
            case self::X_XSS_PROTECTION:
                return new Header\XXSSProtection($value);
            case self::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptions($value);
            case self::REFERRER_POLICY:
                return new Header\ReferrerPolicy($value);
            case self::SET_COOKIE:
                return new Header\SetCookie($value);
            default:
                return new Header\NonSecurityHeader($value);
        }
    }
}
