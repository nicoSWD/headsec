<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithInsufficientMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning;

final class StrictTransportSecurityProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $headerResult = $parsedHeaders->getStrictTransportSecurityResult();
        $observations = [];

        if ($headerResult) {
            if (!$headerResult->hasSecureMaxAge()) {
                $observations[] = new StrictTransportSecurityWithInsufficientMaxAgeWarning();
            }

            if (!$headerResult->hasFlagIncludeSubDomains()) {
                $observations[] = new StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning();
            }

            $auditionResult->addResult($headerResult->name(), $headerResult->value(), $observations);
        }
    }
}
