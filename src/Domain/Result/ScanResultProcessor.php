<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class ScanResultProcessor
{
    /** @var ScoreCalculator */
    private $scoreCalculator;
    /** @var SecurityHeader */
    private $securityHeader;
    /** @var ProcessorFactory */
    private $processorFactory;

    public function __construct(
        ScoreCalculator $scoreCalculator,
        SecurityHeader $securityHeader,
        ProcessorFactory $processorFactory
    ) {
        $this->scoreCalculator = $scoreCalculator;
        $this->securityHeader = $securityHeader;
        $this->processorFactory = $processorFactory;
    }

    public function processParsedHeaders(ParsedHeaders $parsedHeaders): AuditionResult
    {
        $auditionResult = new AuditionResult();

        foreach ($parsedHeaders->getHeaders() as $parsedHeader) {
            $processor = $this->processorFactory->create($parsedHeader->name(), $parsedHeader->value());
            $processor->process($parsedHeaders, $auditionResult);
        }

        return $auditionResult;
    }
}
