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
        switch ($header->getName()) {
            case SecurityHeader::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurityHeader($header->getValue());
            case SecurityHeader::X_FRAME_OPTIONS:
                return new Header\XFrameOptionsHeader($header->getValue());
            case SecurityHeader::X_XSS_PROTECTION:
                return new Header\XXSSProtectionHeader($header->getValue());
            case SecurityHeader::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptionsHeader($header->getValue());
            case SecurityHeader::REFERRER_POLICY:
                return new Header\ReferrerPolicyHeader($header->getValue());
            case SecurityHeader::SET_COOKIE:
                return new Header\SetCookieHeader($header->getValue());
            case SecurityHeader::SERVER:
                return new Header\ServerHeader($header->getValue());
            case SecurityHeader::X_POWERED_BY:
                return new Header\XPoweredByHeader($header->getValue());
            case SecurityHeader::CONTENT_SECURITY_POLICY:
                return new Header\ContentSecurityPolicyHeader($header->getValue());
            default:
                return new Header\NonSecurityHeader($header->getValue());
        }
    }
}
