<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\UseCase\ScanHeaders;

use nicoSWD\SecHeaderCheck\Domain\Header\URLScanner;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterFactory;

final class ScanURLUseCase
{
    /** @var URLScanner */
    private $securityScanner;
    /** @var ResultPrinterFactory */
    private $resultPrinterFactory;

    public function __construct(URLScanner $securityScanner, ResultPrinterFactory $resultPrinterFactory)
    {
        $this->securityScanner = $securityScanner;
        $this->resultPrinterFactory = $resultPrinterFactory;
    }

    public function execute(ScanURLRequest $request): ScanURLResponse
    {
        $outputOptions = new OutputOptions();
        $outputOptions->setShowAllHeaders($request->showAllHeaders);

        $scanResults = $this->securityScanner->scanURL($request->url, $request->followRedirects);
        $outputPrinter = $this->resultPrinterFactory->createFromFormat($request->outputFormat, $outputOptions);

        $result = new ScanURLResponse();
        $result->output = $outputPrinter->getOutput($scanResults);
        $result->score = $scanResults->getScore();
        $result->hitTargetScore = $scanResults->getScore() >= $request->targetScore;

        return $result;
    }
}
