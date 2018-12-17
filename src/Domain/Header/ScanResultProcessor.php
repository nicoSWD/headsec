<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ScoreCalculator;

final class ScanResultProcessor
{
    /** @var ScoreCalculator */
    private $scoreCalculator;

    public function __construct(ScoreCalculator $scoreCalculator)
    {
        $this->scoreCalculator = $scoreCalculator;
    }

    public function processScanResults(ScanResult $scanResult): void
    {
        $scanResult->setScore($this->scoreCalculator->calculateScore($scanResult));

        if (!$this->hasSecureContentSecurityPolicyOrXFrameOptions($scanResult)) {

        }
    }

    private function hasSecureContentSecurityPolicyOrXFrameOptions(ScanResult $scanResult): bool
    {
        $contentSecurityPolicyHeaders = $scanResult->getContentSecurityPolicyResult();
        $hasSecureFrameAncestors = false;
        $hasSecureXFrameOptions = false;

        foreach ($contentSecurityPolicyHeaders as $contentSecurityPolicyHeader) {
            if ($contentSecurityPolicyHeader->hasSecureFrameAncestorsDirective()) {
                $hasSecureFrameAncestors = true;
                break;
            }
        }

        $xFrameOptionsHeader = $scanResult->getXFrameOptionsResult();

        if ($xFrameOptionsHeader && $xFrameOptionsHeader->isSecure()) {
            $hasSecureXFrameOptions = true;
        }

        return !$hasSecureFrameAncestors && !$hasSecureXFrameOptions;
    }
}
