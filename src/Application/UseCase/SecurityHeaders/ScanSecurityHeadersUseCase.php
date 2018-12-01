<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders;

use nicoSWD\SecHeaderCheck\Domain\Headers\HeaderService;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterFactory;

final class ScanSecurityHeadersUseCase
{
    /** @var HeaderService */
    private $headerService;
    /** @var ResultPrinterFactory */
    private $resultPrinterFactory;

    public function __construct(HeaderService $headerService, ResultPrinterFactory $resultPrinterFactory)
    {
        $this->headerService = $headerService;
        $this->resultPrinterFactory = $resultPrinterFactory;
    }

    public function execute(ScanSecurityHeadersRequest $request): ScanSecurityHeadersResponse
    {
        $scanResults = $this->headerService->scan($request->url);
        $outputPrinter = $this->resultPrinterFactory->createFromFormat($request->outputFormat);

        $result = new ScanSecurityHeadersResponse();
        $result->output = $outputPrinter->getOutput($scanResults);
        $result->score = $scanResults->getScore();
        $result->hitTargetScore = $scanResults->getScore() >= (float) $request->targetScore;

        return $result;
    }
}
