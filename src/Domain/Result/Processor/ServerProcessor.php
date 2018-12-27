<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\ServerHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ServerDisclosedVersionNumberWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ServerDoesNotLeakVersionKudos;

final class ServerProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if ($this->header()->leaksServerVersion()) {
            $observations->addWarning(new ServerDisclosedVersionNumberWarning());
        } else {
            $observations->addKudos(new ServerDoesNotLeakVersionKudos());
        }

        $this->addObservations($observations);
    }

    private function header(): ServerHeaderResult
    {
        return $this->parsedHeader;
    }
}
