<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class JSONResultPrinter implements ResultPrinterInterface
{
    public function getOutput(AuditionResult $scanResults, OutputOptions $outputOptions): string
    {
        $data = [
            'score'    => '',
            'warnings' => '',
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
