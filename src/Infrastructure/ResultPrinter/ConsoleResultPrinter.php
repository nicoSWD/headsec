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

//        $output .= '╭'.str_repeat('─', 27) . "\u{2530}" . str_repeat('─', 55) . PHP_EOL;
//        $output .= '    <options=bold>' . str_pad('Header', 25, ' ') . '</>   ' .'<options=bold>Warning</>' . PHP_EOL;
//        $output .= '├'.str_repeat('─', 27) . '┼' . str_repeat('─', 55) . PHP_EOL;

        $maxHeaderLength = 0;

        foreach (array_keys($scanResults->getWarnings()) as $name) {
            $length = strlen($name);
            if ($length > $maxHeaderLength) {
                $maxHeaderLength = $length;
            }
        }

        foreach ($scanResults->getWarnings() as $headerName => $warning) {
            $hasWarnings = count($warning) > 0;

            $line = '[<fg=' . ($hasWarnings ? 'red>-' : 'green>+') . '</>] <bg=' . ($hasWarnings ? 'red' : 'default') . ';fg='.($hasWarnings ? 'black' : '').'>' . str_pad($this->prettyName($headerName), $maxHeaderLength, ' ') . '</> : ' . ($warning ? implode(', ', $warning) : 'Okay') . PHP_EOL;

            $output .= $line;
//            $output .= '├'.str_repeat('─', 27) . '┼' . str_repeat('─', 55) . PHP_EOL;
        }

        $output .= PHP_EOL .'    Total Score: <comment>' . $scanResults->getScore() . '</comment> out of <comment>10</comment> (<fg=red>Fail</>)' . PHP_EOL;
//        $output .= '╰'.str_repeat('─', 28) . '' . str_repeat('─', 55) . PHP_EOL;

        return $output;
    }

    private function prettyName($headerName)
    {
        return implode('-', array_map('ucfirst', explode('-', $headerName)));
    }
}
