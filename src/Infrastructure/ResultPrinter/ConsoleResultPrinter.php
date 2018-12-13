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

        foreach (array_keys($scanResults->getHeaders()) as $name) {
            $length = strlen($name);
            if ($length > $maxHeaderLength) {
                $maxHeaderLength = $length;
            }
        }

        foreach ($scanResults->getHeaders() as $headerName => $header) {
            $hasWarnings = count($header->warnings()) > 0;
            $warning = $header->warnings();

            $line = '[<fg=' . ($hasWarnings ? 'red>-' : 'green>+') . '</>] <bg=' . ($hasWarnings ? 'red' : 'default') . ';fg='.($hasWarnings ? 'black' : '').'>' . str_pad($this->prettyName($headerName), $maxHeaderLength, ' ') . '</> : ' . ($warning ? implode(', ', $warning) : 'No issues') . PHP_EOL;


            $output .= $line;
        }

        $output .= PHP_EOL .'    Total Score: <comment>' . $scanResults->getScore() . '</comment> out of <comment>10</comment> (<fg=red>Fail</>)' . PHP_EOL;

        return $output;
    }

    private function prettyName($headerName)
    {
        return implode('-', array_map('ucfirst', explode('-', $headerName)));
    }
}
