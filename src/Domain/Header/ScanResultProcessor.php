<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;

final class ScanResultProcessor
{
    public function processScanResults(ScanResult $scanResult): void
    {
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
