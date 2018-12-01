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
    public function createFromFormat(string $format): ResultPrinterInterface
    {
        switch ($format) {
            case OutputFormat::CONSOLE:
                return $this->consolePrinter;
            case OutputFormat::JSON:
                return $this->jsonPrinter;
            case OutputFormat::XML:
                return $this->xmlPrinter;
            default:
                throw new Exception\InvalidOutputFormatException();
        }
    }
}
