<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;

final class PostSecurityScanner
{
    public function postScan(ScanResult $scanResult)
    {
        $contentSecurityPolicyHeaders = $scanResult->getContentSecurityPolicyResult();

        foreach ($contentSecurityPolicyHeaders as $contentSecurityPolicyHeader) {
            if (!$contentSecurityPolicyHeader->hasSecureFrameAncestorsDirective()) {

            }
        }
    }
}
