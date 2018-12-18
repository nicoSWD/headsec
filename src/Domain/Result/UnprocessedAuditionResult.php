<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class UnprocessedAuditionResult
{
    /** @var ParsedHeaderBag */
    private $headers;
    private $score = .0;

    public function __construct()
    {
        $this->headers = new ParsedHeaderBag();
    }

    public function add(AbstractHeaderAuditResult $header): void
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

    public function getXFrameOptionsResult(): ?XFrameOptionsResult
    {
        return $this->headers->findOne(SecurityHeader::X_FRAME_OPTIONS);
    }

    public function getStrictTransportSecurityResult(): ?StrictTransportSecurityHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::STRICT_TRANSPORT_SECURITY);
    }

    public function getReferrerPolicyResult(): ?ReferrerPolicyHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::REFERRER_POLICY);
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score)
    {
        $this->score = $score;
    }
}
