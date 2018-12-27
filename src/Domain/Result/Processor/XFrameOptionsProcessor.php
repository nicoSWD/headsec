<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XFrameOptionsWithInsecureValueError;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XFrameOptionsHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XFrameOptionsWithSecureOriginKudos;

final class XFrameOptionsProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->getHasSecureOrigin() && !$this->header()->hasAllowFrom()) {
            $observations->addError(new XFrameOptionsWithInsecureValueError());
        } else {
            $observations->addKudos(new XFrameOptionsWithSecureOriginKudos());
        }

        $this->addObservations($observations);
    }

    private function header(): XFrameOptionsHeaderResult
    {
        return $this->parsedHeader;
    }
}
