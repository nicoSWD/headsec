<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\ContentSecurityPolicyResult;
use nicoSWD\SecHeaderCheck\Domain\Result\GenericHeaderAuditResult;

final class HeaderValidatorFactory
{
    public function createFromHeader(HttpHeader $header): GenericHeaderAuditResult
    {
        switch ($header->name()){
            case SecurityHeader::STRICT_TRANSPORT_SECURITY:
                return new GenericHeaderAuditResult(
                    (new Header\StrictTransportSecurityHeader($header))->auditHeader()
                );
            case SecurityHeader::X_FRAME_OPTIONS:
                return new GenericHeaderAuditResult(
                    (new Header\XFrameOptionsHeader($header))->auditHeader()
                );
            case SecurityHeader::X_XSS_PROTECTION:
                return new GenericHeaderAuditResult(
                    (new Header\XXSSProtectionHeader($header))->auditHeader()
                );
            case SecurityHeader::X_CONTENT_TYPE_OPTIONS:
                return new GenericHeaderAuditResult(
                    (new Header\XContentTypeOptionsHeader($header))->auditHeader()
                );
            case SecurityHeader::REFERRER_POLICY:
                return new GenericHeaderAuditResult(
                    (new Header\ReferrerPolicyHeader($header))->auditHeader()
                );
            case SecurityHeader::SET_COOKIE:
                return new GenericHeaderAuditResult(
                    (new Header\SetCookieHeader($header))->auditHeader()
                );
            case SecurityHeader::SERVER:
                return new GenericHeaderAuditResult(
                    (new Header\ServerHeader($header))->auditHeader()
                );
            case SecurityHeader::X_POWERED_BY:
                return new GenericHeaderAuditResult(
                    (new Header\XPoweredByHeader($header))->auditHeader()
                );
            case SecurityHeader::CONTENT_SECURITY_POLICY:
                return new ContentSecurityPolicyResult(
                    (new Header\ContentSecurityPolicyHeader($header))->auditHeader()
                );
            default:
                return new GenericHeaderAuditResult(
                    (new Header\NonSecurityHeader($header))->auditHeader()
                );
        }
    }
}
