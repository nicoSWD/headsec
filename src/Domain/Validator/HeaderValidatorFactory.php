<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeaders;

final class HeaderValidatorFactory
{
    public function createFromHeader(?string $headerName, $value): AbstractHeaderValidator
    {
        if ($value === null) {
            return new Header\MissingHeader();
        }

        switch ($headerName) {
            case SecurityHeaders::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurityHeader($value);
            case SecurityHeaders::X_FRAME_OPTIONS:
                return new Header\XFrameOptionsHeader($value);
            case SecurityHeaders::X_XSS_PROTECTION:
                return new Header\XXSSProtectionHeader($value);
            case SecurityHeaders::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptionsHeader($value);
            case SecurityHeaders::REFERRER_POLICY:
                return new Header\ReferrerPolicyHeader($value);
            case SecurityHeaders::SET_COOKIE:
                return new Header\SetCookieHeader($value);
            case SecurityHeaders::SERVER:
                return new Header\ServerHeader($value);
            case SecurityHeaders::X_POWERED_BY:
                return new Header\XPoweredByHeader($value);
            case SecurityHeaders::CONTENT_SECURITY_POLICY:
                return new Header\ContentSecurityPolicyHeader($value);
            default:
                return new Header\NonSecurityHeader($value);
        }
    }
}
