<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithInvalidValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithLeakingOriginWarning;

final class ReferrerPolicyProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $headerResult = $parsedHeaders->getReferrerPolicyResult();

        if ($headerResult) {
            $observations = [];

            if ($headerResult->isMayLeakOrigin()) {
                $observations[] = new ReferrerPolicyWithLeakingOriginWarning();
            } elseif (!$headerResult->doesNotLeakReferrer()) {
                $observations[] = new ReferrerPolicyWithInvalidValueWarning();
            }

            $auditionResult->addResult($headerResult->name(), $headerResult->value(), $observations);
        }
    }
}
