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
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionTurnedOffWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutModeBlockWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutReportURIWarning;

final class XXSSProtectionProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->protectionIsOn()) {
            $observations->attach(new XXSSProtectionTurnedOffWarning());
        }

        if (!$this->header()->isBlocking()) {
            $observations->attach(new XXSSProtectionWithoutModeBlockWarning());
        }

        if (!$this->header()->hasReportUri()) {
            $observations->attach(new XXSSProtectionWithoutReportURIWarning());
        }

        $this->addObservations($observations);
    }

    private function header(): XXSSProtectionHeaderResult
    {
        return $this->parsedHeader;
    }
}
