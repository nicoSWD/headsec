<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ContentSecurityPolicyMissingFrameAncestorsDirectiveWarning;

final class ContentSecurityPolicyProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $contentSecurityPolicyHeaders = $parsedHeaders->getContentSecurityPolicyResult();
        $hasSecureFrameAncestors = false;
        $hasSecureXFrameOptions = false;
        $observations = [];

        foreach ($contentSecurityPolicyHeaders as $contentSecurityPolicyHeader) {
            if ($contentSecurityPolicyHeader->isSecure()) {
                $hasSecureFrameAncestors = true;
                break;
            }
        }

        $xFrameOptionsHeader = $parsedHeaders->getXFrameOptionsResult();

        if ($xFrameOptionsHeader && $xFrameOptionsHeader->isSecure()) {
            $hasSecureXFrameOptions = true;
        }

        if (!$hasSecureXFrameOptions) {
            if ($contentSecurityPolicyHeaders && !$hasSecureFrameAncestors) {
                $observations[] = new ContentSecurityPolicyMissingFrameAncestorsDirectiveWarning();
            }
        }

        $auditionResult->addResult($contentSecurityPolicyHeaders[0]->name(), $contentSecurityPolicyHeaders[0]->value(), $observations);
    }
}
