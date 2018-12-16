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

    public function __construct()
    {
        $this->headers = new EvaluatedHeaderBag();
    }

    public function addHeaderResult(EvaluatedHeader $header): void
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

    public function getScore(): float
    {
        $score = 0;

        foreach ($this->headers as $header) {
            $score += $header->score();
        }

        return $score;
    }
}
