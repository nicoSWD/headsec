<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;

final class HeaderValidatorFactory
{
    public function createFromHeader(HttpHeader $header): AbstractHeaderValidator
    {
        switch ($header->name()) {
            case SecurityHeader::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurityHeader($header->value());
            case SecurityHeader::X_FRAME_OPTIONS:
                return new Header\XFrameOptionsHeader($header->value());
            case SecurityHeader::X_XSS_PROTECTION:
                return new Header\XXSSProtectionHeader($header->value());
            case SecurityHeader::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptionsHeader($header->value());
            case SecurityHeader::REFERRER_POLICY:
                return new Header\ReferrerPolicyHeader($header->value());
            case SecurityHeader::SET_COOKIE:
                return new Header\SetCookieHeader($header->value());
            case SecurityHeader::SERVER:
                return new Header\ServerHeader($header->value());
            case SecurityHeader::X_POWERED_BY:
                return new Header\XPoweredByHeader($header->value());
            case SecurityHeader::CONTENT_SECURITY_POLICY:
                return new Header\ContentSecurityPolicyHeader($header->value());
            default:
                return new Header\NonSecurityHeader($header->value());
        }
    }
}
