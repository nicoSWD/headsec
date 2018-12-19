<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class ParsedHeaders
{
    /** @var ParsedHeaderBag */
    private $headers;

    public function __construct()
    {
        $this->headers = new ParsedHeaderBag();
    }

    public function add(AbstractParsedHeader $header): void
    {
        $this->headers->add($header);
    }

    public function getHeaders(): ParsedHeaderBag
    {
        return $this->headers;
    }

    /** @return ContentSecurityPolicyHeaderResult[] */
    public function getContentSecurityPolicyResult()
    {
        return $this->headers->findMultiple(SecurityHeader::CONTENT_SECURITY_POLICY);
    }

    /** @return SetCookieResult[] */
    public function getSetCookieResult()
    {
        return $this->headers->findMultiple(SecurityHeader::SET_COOKIE);
    }

    public function getXPoweredByResult(): ?XPoweredByHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::X_POWERED_BY);
    }

    public function getXFrameOptionsResult(): ?XFrameOptionsResult
    {
        return $this->headers->findOne(SecurityHeader::X_FRAME_OPTIONS);
    }

    public function getServerResult(): ?ServerResult
    {
        return $this->headers->findOne(SecurityHeader::SERVER);
    }

    public function getStrictTransportSecurityResult(): ?StrictTransportSecurityHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::STRICT_TRANSPORT_SECURITY);
    }

    public function getReferrerPolicyResult(): ?ReferrerPolicyHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::REFERRER_POLICY);
    }

    public function getXXSSProtectionResult(): ?XXSSProtectionHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::X_XSS_PROTECTION);
    }

    public function getXContentTypeOptionsResult(): ?XContentTypeOptionsHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::X_CONTENT_TYPE_OPTIONS);
    }
}
