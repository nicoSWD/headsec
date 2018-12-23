<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders;

use nicoSWD\SecHeaderCheck\Domain\Header\HeaderScanner;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterFactory;

final class ScanHeadersUseCase
{
    /** @var HeaderScanner */
    private $securityScanner;
    /** @var ResultPrinterFactory */
    private $resultPrinterFactory;

    public function __construct(HeaderScanner $securityScanner, ResultPrinterFactory $resultPrinterFactory)
    {
        $this->securityScanner = $securityScanner;
        $this->resultPrinterFactory = $resultPrinterFactory;
    }

    public function execute(ScanHeadersRequest $request): ScanHeadersResponse
    {
        $outputOptions = new OutputOptions();
        $outputOptions->setShowAllHeaders($request->showAllHeaders);

        $scanResults = $this->securityScanner->scanHeaders($request->headers);
        $outputPrinter = $this->resultPrinterFactory->createFromFormat($request->outputFormat, $outputOptions);

        $result = new ScanHeadersResponse();
        $result->output = $outputPrinter->getOutput($scanResults);
        $result->score = $scanResults->getScore();
        $result->hitTargetScore = $scanResults->getScore() >= $request->targetScore;

        return $result;
    }
}
