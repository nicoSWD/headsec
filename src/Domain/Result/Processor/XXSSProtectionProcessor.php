<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XXSSProtectionHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionHasReportUriKudos;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionIsBlockingKudos;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionTurnedOffWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionTurnedOnKudos;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutModeBlockWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutReportURIInfo;

final class XXSSProtectionProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->protectionIsOn()) {
            $observations->addWarning(new XXSSProtectionTurnedOffWarning());
        } else {
            $observations->addKudos(new XXSSProtectionTurnedOnKudos());
        }

        if (!$this->header()->isBlocking()) {
            $observations->addWarning(new XXSSProtectionWithoutModeBlockWarning());
        } else {
            $observations->addKudos(new XXSSProtectionIsBlockingKudos());
        }

        if (!$this->header()->hasReportUri()) {
            $observations->addInfo(new XXSSProtectionWithoutReportURIInfo());
        } else {
            $observations->addKudos(new XXSSProtectionHasReportUriKudos());
        }

        $this->addObservations($observations);
    }

    private function header(): XXSSProtectionHeaderResult
    {
        return $this->parsedHeader;
    }
}
