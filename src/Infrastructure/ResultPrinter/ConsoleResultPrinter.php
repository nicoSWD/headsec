<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Result\UnprocessedAuditionResult;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class ConsoleResultPrinter implements ResultPrinterInterface
{
    public function getOutput(UnprocessedAuditionResult $scanResults): string
    {
        $output = '  <fg=white>Security Headers Check v1.2</>' . PHP_EOL . PHP_EOL;
        $maxHeaderLength = $this->getHeaderMaxLength($scanResults);

        $totalWarnings = 0;

        foreach ($scanResults->getHeaders() as $header) {
            $headerName = $header->name();

            if (!$header->isSecure()) {
                $totalWarnings++;

                $line = '  <bg=' . (true ? 'red' : 'default') . ';fg=' . (true ? 'black' : '') . '> ' . str_pad($this->prettyName($headerName),
                        $maxHeaderLength, ' ') . ' </>' . $this->getWarnings('Oh no') . PHP_EOL;
                $output .= $line;
            }
        }

        if ($totalWarnings === 0) {
            $output .= '  <bg=green;fg=black>                            </>' . PHP_EOL ;
            $output .= '  <bg=green;fg=black>   Congrats, no warnings!   </>' . PHP_EOL ;
            $output .= '  <bg=green;fg=black>                            </>' . PHP_EOL ;
        }

        $output .= PHP_EOL .'  Total Score: <comment>' . $scanResults->getScore() . '</comment> out of <comment>10</comment> (<fg=red>Fail</>)' . PHP_EOL;

        return $output;
    }

    private function prettyName($headerName)
    {
        return implode('-', array_map('ucfirst', explode('-', $headerName)));
    }

    private function getWarnings($warning): string
    {
        return ($warning ? '<bg=red;fg=black> ' . (string) $warning . ' </>': '<bg=green;fg=black> No issues </>');
    }

    private function getHeaderMaxLength(UnprocessedAuditionResult $scanResults): int
    {
        $maxHeaderLength = 0;

        foreach ($scanResults->getHeaders() as $header) {
            $length = strlen($header->name());
            if (!$header->isSecure() && $length > $maxHeaderLength) {
                $maxHeaderLength = $length;
            }
        }

        return $maxHeaderLength;
    }
}
