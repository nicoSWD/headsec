<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XXSSProtectionHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionTurnedOffWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutModeBlockWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XXSSProtectionWithoutReportURIWarning;

final class XXSSProtectionProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = [];

        if (!$this->header()->protectionIsOn()) {
            $observations[] = new XXSSProtectionTurnedOffWarning();
        }

        if (!$this->header()->isBlocking()) {
            $observations[] = new XXSSProtectionWithoutModeBlockWarning();
        }

        if (!$this->header()->hasReportUri()) {
            $observations[] = new XXSSProtectionWithoutReportURIWarning();
        }

        $this->addResult($observations);
    }

    private function header(): XXSSProtectionHeaderResult
    {
        return $this->parsedHeader;
    }
}
