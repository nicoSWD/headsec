<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityScanner;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterFactory;

final class ScanSecurityHeadersUseCase
{
    /** @var SecurityScanner */
    private $headerService;
    /** @var ResultPrinterFactory */
    private $resultPrinterFactory;

    public function __construct(SecurityScanner $headerService, ResultPrinterFactory $resultPrinterFactory)
    {
        $this->headerService = $headerService;
        $this->resultPrinterFactory = $resultPrinterFactory;
    }

    public function execute(ScanSecurityHeadersRequest $request): ScanSecurityHeadersResponse
    {
        $outputOptions = new OutputOptions();
        $outputOptions->setShowAllHeaders($request->showAllHeaders);

        $scanResults = $this->headerService->scan($request->url, $request->followRedirects);
        $outputPrinter = $this->resultPrinterFactory->createFromFormat($request->outputFormat, $outputOptions);

        $result = new ScanSecurityHeadersResponse();
        $result->output = $outputPrinter->getOutput($scanResults);
        $result->score = $scanResults->getScore();
        $result->hitTargetScore = $scanResults->getScore() >= $request->targetScore;

        return $result;
    }
}
