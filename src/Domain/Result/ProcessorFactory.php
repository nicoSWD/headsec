<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\AbstractProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\ContentSecurityPolicyProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\ExpectCTProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\NonSecurityHeaderProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\ReferrerPolicyProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\ServerProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\SetCookieProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\StrictTransportSecurityProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\XContentTypeOptionsProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\XFrameOptionsProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\XPoweredByProcessor;
use nicoSWD\SecHeaderCheck\Domain\Result\Processor\XXSSProtectionProcessor;

final class ProcessorFactory
{
    public function create(string $headerName, string $headerValue): AbstractProcessor
    {
        switch ($headerName) {
            case SecurityHeader::CONTENT_SECURITY_POLICY:
                return new ContentSecurityPolicyProcessor($headerName, $headerValue);
            case SecurityHeader::EXPECT_CT:
                return new ExpectCTProcessor($headerName, $headerValue);
            case SecurityHeader::REFERRER_POLICY:
                return new ReferrerPolicyProcessor($headerName, $headerValue);
            case SecurityHeader::SET_COOKIE:
                return new SetCookieProcessor($headerName, $headerValue);
            case SecurityHeader::SERVER:
                return new ServerProcessor($headerName, $headerValue);
            case SecurityHeader::STRICT_TRANSPORT_SECURITY:
                return new StrictTransportSecurityProcessor($headerName, $headerValue);
            case SecurityHeader::X_FRAME_OPTIONS:
                return new XFrameOptionsProcessor($headerName, $headerValue);
            case SecurityHeader::X_CONTENT_TYPE_OPTIONS:
                return new XContentTypeOptionsProcessor($headerName, $headerValue);
            case SecurityHeader::X_POWERED_BY:
                return new XPoweredByProcessor($headerName, $headerValue);
            case SecurityHeader::X_XSS_PROTECTION:
                return new XXSSProtectionProcessor($headerName, $headerValue);
            default:
                return new NonSecurityHeaderProcessor($headerName, $headerValue);
        }
    }
}
