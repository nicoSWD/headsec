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

    public function __construct(
        ResultPrinterInterface $jsonPrinter,
        ResultPrinterInterface $xmlPrinter
    ) {
        $this->jsonPrinter = $jsonPrinter;
        $this->xmlPrinter = $xmlPrinter;
    }

    /** @throws Exception\InvalidOutputFormatException */
    public function createFromFormat(string $format): ResultPrinterInterface
    {
        switch ($format) {
            case Format::JSON:
                return $this->jsonPrinter;
            case Format::XML:
                return $this->xmlPrinter;
            default:
                throw new Exception\InvalidOutputFormatException();
        }
    }
}
