<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class HeaderValidatorFactory
{
    public function createFromHeader(HttpHeader $header): AbstractHeaderValidator
    {
        switch ($header->name()) {
            case SecurityHeader::STRICT_TRANSPORT_SECURITY:
                return new Header\StrictTransportSecurityHeader($header);
            case SecurityHeader::X_FRAME_OPTIONS:
                return new Header\XFrameOptionsHeader($header);
            case SecurityHeader::X_XSS_PROTECTION:
                return new Header\XXSSProtectionHeader($header);
            case SecurityHeader::X_CONTENT_TYPE_OPTIONS:
                return new Header\XContentTypeOptionsHeader($header);
            case SecurityHeader::REFERRER_POLICY:
                return new Header\ReferrerPolicyHeader($header);
            case SecurityHeader::SET_COOKIE:
                return new Header\SetCookieHeader($header);
            case SecurityHeader::SERVER:
                return new Header\ServerHeader($header);
            case SecurityHeader::X_POWERED_BY:
                return new Header\XPoweredByHeader($header);
            case SecurityHeader::CONTENT_SECURITY_POLICY:
                return new Header\ContentSecurityPolicyHeader($header);
            case SecurityHeader::EXPECT_CT:
                // Pending
            default:
                return new Header\NonSecurityHeader($header);
        }
    }
}
