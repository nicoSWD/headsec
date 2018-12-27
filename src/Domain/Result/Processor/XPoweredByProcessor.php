<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XPoweredByDisclosesTechnologyInfo;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XPoweredByHeaderResult;

final class XPoweredByProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->isSecure()) {
            $observations->addInfo(new XPoweredByDisclosesTechnologyInfo());
        }

        $this->addObservations($observations);
    }

    private function header(): XPoweredByHeaderResult
    {
        return $this->parsedHeader;
    }
}
