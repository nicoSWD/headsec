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

    public function __construct(ResultPrinterInterface $jsonPrinter)
    {
        $this->jsonPrinter = $jsonPrinter;
    }

    public function createFromFormat(string $format): ResultPrinterInterface
    {
        switch ($format) {
            case 'json':
                return $this->jsonPrinter;
            default:
                throw new \Exception("Invalid format: {$format}");
        }
    }
}
