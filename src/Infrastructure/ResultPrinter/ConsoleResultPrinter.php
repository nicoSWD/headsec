<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class ConsoleResultPrinter implements ResultPrinterInterface
{
    public function getOutput(ScanResult $scanResults): string
    {
        $output = '<fg=white>Security Headers Check v1.2</>' . PHP_EOL . PHP_EOL;
        $maxHeaderLength = 0;

        foreach ($scanResults->getHeaders() as $header) {
            $length = strlen($header->name());
            if ($length > $maxHeaderLength) {
                $maxHeaderLength = $length;
            }
        }

        foreach ($scanResults->getHeaders() as $header) {
            $headerName = $header->name();
            $hasWarnings = count($header->getEvaluatedHeader()->warnings()) > 0;
            $warning = $header->getEvaluatedHeader()->warnings();

//            if ($hasWarnings) {
                $line = '[<fg=' . ($hasWarnings ? 'red>-' : 'green>+') . '</>] <bg=' . ($hasWarnings ? 'red' : 'default') . ';fg=' . ($hasWarnings ? 'white' : '') . '>' . str_pad($this->prettyName($headerName),
                        $maxHeaderLength, ' ') . ' </>' . $this->getWarnings($warning) . PHP_EOL;
                $output .= $line;
//            }
        }

        $output .= PHP_EOL .'    Total Score: <comment>' . $scanResults->getScore() . '</comment> out of <comment>10</comment> (<fg=red>Fail</>)' . PHP_EOL;

        return $output;
    }

    private function prettyName($headerName)
    {
        return implode('-', array_map('ucfirst', explode('-', $headerName)));
    }

    private function getWarnings($warning): string
    {
        return ($warning ? '<bg=red;fg=black> ' . (string) ($warning[0]) . ' </>': '<bg=green;fg=black> No issues </>');
    }
}
