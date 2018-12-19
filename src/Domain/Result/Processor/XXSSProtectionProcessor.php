<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionTurnedOffWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutModeBlockWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutReportURIWarning;

final class XXSSProtectionProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $XXSSProtectionResult = $parsedHeaders->getXXSSProtectionResult();
        $observations = [];

        if ($XXSSProtectionResult) {
            if (!$XXSSProtectionResult->protectionIsOn()) {
                $observations[] = new XXSSProtectionTurnedOffWarning();
            } else {
                if (!$XXSSProtectionResult->isBlocking()) {
                    $observations[] = new XXSSProtectionWithoutModeBlockWarning();
                }

                if (!$XXSSProtectionResult->hasReportUri()) {
                    $observations[] = new XXSSProtectionWithoutReportURIWarning();
                }
            }
        }

        $auditionResult->addResult($this->getHeaderName(), $this->getHeaderValue(), $observations);
    }
}
