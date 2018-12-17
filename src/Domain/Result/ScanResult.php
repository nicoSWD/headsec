<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class ScanResult
{
    /** @var EvaluatedHeaderBag */
    private $headers;
    private $score = .0;

    public function __construct()
    {
        $this->headers = new EvaluatedHeaderBag();
    }

    public function addHeaderResult(GenericHeaderAuditResult $header): void
    {
        $this->headers->add($header);
    }

    public function getHeaders(): EvaluatedHeaderBag
    {
        return $this->headers;
    }

    /** @return ContentSecurityPolicyResult[] */
    public function getContentSecurityPolicyResult()
    {
        return $this->headers->findMultiple(SecurityHeader::CONTENT_SECURITY_POLICY);
    }

    public function getXFrameOptionsResult(): ?XFrameOptionsResult
    {
        return $this->headers->findOne(SecurityHeader::X_FRAME_OPTIONS);
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
