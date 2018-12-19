<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\ResultPrinter;

final class ResultPrinterFactory
{
    /** @var ResultPrinterInterface */
    private $jsonPrinter;
    /** @var ResultPrinterInterface */
    private $xmlPrinter;
    /** @var ResultPrinterInterface */
    private $consolePrinter;

    public function __construct(
        ResultPrinterInterface $jsonPrinter,
        ResultPrinterInterface $xmlPrinter,
        ResultPrinterInterface $consolePrinter
    ) {
        $this->jsonPrinter = $jsonPrinter;
        $this->xmlPrinter = $xmlPrinter;
        $this->consolePrinter = $consolePrinter;
    }

    /** @throws Exception\InvalidOutputFormatException */
    public function createFromFormat(string $format, OutputOptions $outputOptions): ResultPrinter
    {
        switch ($format) {
            case OutputFormat::CONSOLE:
                return new ResultPrinter($this->consolePrinter, $outputOptions);
            case OutputFormat::JSON:
                return new ResultPrinter($this->jsonPrinter, $outputOptions);
            case OutputFormat::XML:
                return new ResultPrinter($this->xmlPrinter, $outputOptions);
            default:
                throw new Exception\InvalidOutputFormatException();
        }
    }
}
