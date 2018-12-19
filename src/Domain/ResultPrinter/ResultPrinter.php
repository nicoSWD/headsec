<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;

final class ResultPrinter
{
    /** @var ResultPrinterInterface */
    private $resultPrinter;
    /** @var OutputOptions */
    private $outputOptions;

    public function __construct(ResultPrinterInterface $resultPrinter, OutputOptions $outputOptions)
    {
        $this->resultPrinter = $resultPrinter;
        $this->outputOptions = $outputOptions;
    }

    public function getOutput(AuditionResult $auditionResult): string
    {
        return $this->resultPrinter->getOutput($auditionResult, $this->outputOptions);
    }
}
