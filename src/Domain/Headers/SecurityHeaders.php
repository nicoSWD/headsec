<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Headers;

final class SecurityHeaders
{
    public const STRICT_TRANSPORT_SECURITY = 'strict-transport-security';
    public const X_FRAME_OPTIONS = 'x-frame-options';
    public const X_XSS_PROTECTION = 'x-xss-protection';
    public const X_CONTENT_TYPE_OPTIONS = 'x-content-type-options';
    public const REFERRER_POLICY = 'referrer-policy';
    public const SET_COOKIE = 'set-cookie';

    public function all(): array
    {
        return [
            self::STRICT_TRANSPORT_SECURITY,
            self::X_FRAME_OPTIONS,
            self::X_XSS_PROTECTION,
            self::X_CONTENT_TYPE_OPTIONS,
            self::REFERRER_POLICY,
//            self::SET_COOKIE,
        ];
    }
}
