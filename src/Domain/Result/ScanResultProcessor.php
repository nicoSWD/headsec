<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ScanResultProcessor
{
    /** @var ScoreCalculator */
    private $scoreCalculator;
    /** @var ProcessorFactory */
    private $processorFactory;
    /** @var AuditionResult */
    private $auditionResult;

    public function __construct(
        ScoreCalculator $scoreCalculator,
        ProcessorFactory $processorFactory,
        AuditionResult $auditionResult
    ) {
        $this->scoreCalculator = $scoreCalculator;
        $this->processorFactory = $processorFactory;
        $this->auditionResult = $auditionResult;
    }

    public function processParsedHeaders(ParsedHeaders $parsedHeaders): AuditionResult
    {
        foreach ($parsedHeaders->all() as $parsedHeader) {
            $processor = $this->processorFactory->create($parsedHeader, $this->auditionResult);
            $processor->process($parsedHeaders);
        }

        return $this->auditionResult;
    }
}
