<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

final class SecurityHeaders
{
    public const STRICT_TRANSPORT_SECURITY = 'strict-transport-security';
    public const CONTENT_SECURITY_POLICY = 'content-security-policy';
    public const X_FRAME_OPTIONS = 'x-frame-options';
    public const X_XSS_PROTECTION = 'x-xss-protection';
    public const X_CONTENT_TYPE_OPTIONS = 'x-content-type-options';
    public const REFERRER_POLICY = 'referrer-policy';
    public const SET_COOKIE = 'set-cookie';
    public const SERVER = 'server';
    public const X_POWERED_BY = 'x-powered-by';

    public const MANDATORY = true;
    public const OPTIONAL = false;

    /** @return Header[] */
    public function getAll(): array
    {
        return [
            new Header(self::STRICT_TRANSPORT_SECURITY, self::MANDATORY),
            new Header(self::X_FRAME_OPTIONS, self::MANDATORY),
            new Header(self::X_XSS_PROTECTION, self::MANDATORY),
            new Header(self::X_CONTENT_TYPE_OPTIONS, self::MANDATORY),
            new Header(self::REFERRER_POLICY, self::MANDATORY),
            new Header(self::CONTENT_SECURITY_POLICY, self::MANDATORY),
            new Header(self::SET_COOKIE, self::OPTIONAL),
            new Header(self::SERVER, self::OPTIONAL),
            new Header(self::X_POWERED_BY, self::OPTIONAL),
        ];
    }
}
