<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\HeaderWithObservations;
use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;

abstract class AbstractProcessor
{
    /** @var AbstractParsedHeader */
    protected $parsedHeader;
    /** @var AuditionResult */
    private $observations;

    abstract public function process(ParsedHeaders $parsedHeaders): void;

    public function __construct(AbstractParsedHeader $parsedHeader, AuditionResult $observations)
    {
        $this->parsedHeader = $parsedHeader;
        $this->observations = $observations;
    }

    protected function addObservations(ObservationCollection $observations): void
    {
        $this->observations->addObservations(
            new HeaderWithObservations(
                $this->parsedHeader->name(),
                $this->parsedHeader->value(),
                $observations
            )
        );
    }
}
