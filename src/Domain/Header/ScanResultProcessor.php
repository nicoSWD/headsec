<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\UnprocessedAuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ScoreCalculator;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ContentSecurityPolicyMissingFrameAncestorsDirectiveWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingHttpOnlyFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSecureFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithInvalidValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithLeakingOriginWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithInsufficientMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning;

final class ScanResultProcessor
{
    /** @var ScoreCalculator */
    private $scoreCalculator;

    public function __construct(ScoreCalculator $scoreCalculator)
    {
        $this->scoreCalculator = $scoreCalculator;
    }

    public function processScanResults(UnprocessedAuditionResult $scanResult): AuditionResult
    {
        $auditionResult = new AuditionResult();

        $this->processSetCookieHeaders($scanResult, $auditionResult);
        $this->processStrictTransportSecurityHeaders($scanResult, $auditionResult);
        $this->processReferrerPolicyHeaders($scanResult, $auditionResult);
        $this->hasSecureContentSecurityPolicyOrXFrameOptions($scanResult, $auditionResult);

        return $auditionResult;
    }

    private function processSetCookieHeaders(UnprocessedAuditionResult $scanResult, AuditionResult $auditionResult)
    {
        foreach ($scanResult->getSetCookieResult() as $headerResult) {
            $observations = [];

            if (!$headerResult->hasFlagHttpOnly()) {
                $observations[] = new CookieWithMissingHttpOnlyFlagWarning();
            }

            if (!$headerResult->hasFlagSecure()) {
                $observations[] = new CookieWithMissingSecureFlagWarning();
            }

            $auditionResult->addObservation($headerResult->name(), $headerResult->value(), $observations);
        }
    }

    private function processStrictTransportSecurityHeaders(UnprocessedAuditionResult $scanResult, AuditionResult $auditionResult)
    {
        $headerResult = $scanResult->getStrictTransportSecurityResult();
        $observations = [];

        if ($headerResult) {
            if (!$headerResult->hasSecureMaxAge()) {
                $observations[] = new StrictTransportSecurityWithInsufficientMaxAgeWarning();
            }

            if (!$headerResult->hasFlagIncludeSubDomains()) {
                $observations[] = new StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning();
            }

            $auditionResult->addObservation($headerResult->name(), $headerResult->value(), $observations);
        } else {
            $auditionResult->addMissingHeader(SecurityHeader::STRICT_TRANSPORT_SECURITY);
        }
    }

    private function processReferrerPolicyHeaders(UnprocessedAuditionResult $scanResult, AuditionResult $auditionResult)
    {
        $headerResult = $scanResult->getReferrerPolicyResult();
        $observations = [];

        if ($headerResult) {
            if ($headerResult->isMayLeakOrigin()) {
                $observations[] = new ReferrerPolicyWithLeakingOriginWarning();
            } elseif (!$headerResult->doesNotLeakReferrer()) {
                $observations[] = new ReferrerPolicyWithInvalidValueWarning();
            }

            $auditionResult->addObservation($headerResult->name(), $headerResult->value(), $observations);
        } else {
            $auditionResult->addMissingHeader(SecurityHeader::REFERRER_POLICY);
        }
    }

    private function hasSecureContentSecurityPolicyOrXFrameOptions(UnprocessedAuditionResult $scanResult, AuditionResult $auditionResult)
    {
        $contentSecurityPolicyHeaders = $scanResult->getContentSecurityPolicyResult();
        $hasSecureFrameAncestors = false;
        $hasSecureXFrameOptions = false;
        $observations = [];

        foreach ($contentSecurityPolicyHeaders as $contentSecurityPolicyHeader) {
            if ($contentSecurityPolicyHeader->isSecure()) {
                $hasSecureFrameAncestors = true;
                break;
            }
        }

        $xFrameOptionsHeader = $scanResult->getXFrameOptionsResult();

        if ($xFrameOptionsHeader && $xFrameOptionsHeader->isSecure()) {
            $hasSecureXFrameOptions = true;
        }

        if (!$hasSecureXFrameOptions) {
            if ($contentSecurityPolicyHeaders && !$hasSecureFrameAncestors) {
                $observations[] = new ContentSecurityPolicyMissingFrameAncestorsDirectiveWarning();
                $auditionResult->addObservation($contentSecurityPolicyHeaders[0]->name(), $contentSecurityPolicyHeaders[0]->value(), $observations);
            }
        }
    }
}
