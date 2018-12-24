<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\HeaderWithObservations;
use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class ConsoleResultPrinter implements ResultPrinterInterface
{
    public function getOutput(AuditionResult $scanResults, OutputOptions $outputOptions): string
    {
        $output = '';
        $totalWarnings = 0;

        foreach ($scanResults->getObservations() as $observations) {
            if (!$observations->getObservations()->count() && !$outputOptions->showAllHeaders()) {
                continue;
            }

            $totalWarnings++;

            if ($this->doesFail($observations)) {
                $res = '<fg=red>Fail </>';
            } else {
                $res = '<fg=green>Pass </>';
            }

            $output .= $res . '<bg=default;fg=white>' . $this->prettyName($observations->getHeaderName()) . ': ' . $this->shortenHeaderValue($observations->getHeaderName(), $observations->getHeaderValue()) . ' </>' . $this->getWarnings($observations->getObservations());
        }

        $missingHeaders = $scanResults->getMissingHeaders();

        if ($missingHeaders) {
            $output .= PHP_EOL . 'Missing headers: ';
        }

        foreach ($missingHeaders as $headerName) {
            $totalWarnings++;
            $output .= '<bg=red;fg=black>' . $this->prettyName($headerName) . '</> ';
        }

        if ($missingHeaders) {
            $output .= PHP_EOL;
        }

        if ($totalWarnings === 0) {
            $output .= '  <bg=green;fg=black>                            </>' . PHP_EOL ;
            $output .= '  <bg=green;fg=black>   Congrats, no warnings!   </>' . PHP_EOL ;
            $output .= '  <bg=green;fg=black>                            </>' . PHP_EOL ;
        }

        $output .= PHP_EOL .'Total Score: <comment>' . $scanResults->getScore() . '</comment> out of <comment>10</comment> (<fg=red>Fail</>)';

        return $output;
    }

    private function prettyName($headerName)
    {
        return '<fg=cyan>' . implode('-', array_map('ucfirst', explode('-', $headerName))) . '</>';
    }

    private function getWarnings(ObservationCollection $observations): string
    {
        $out = '';

        foreach ($observations as $observation) {
            $out .= PHP_EOL . '   =>';

            if ($observation->getPenalty() === .0) {
                $out .= '<fg=yellow> ' . (string) $observation . '</> ';
            } elseif ($observation->getPenalty() === .5) {
                $out .= '<fg=red> ' . (string) $observation . '</> ';
            } else {
                $out .= '<fg=red> ' . (string) $observation . '</> ';
            }
        }

        return $out . PHP_EOL;
    }

    private function shortenHeaderValue(string $headerName, string $headerValue): string
    {
        $width = (int) `tput cols`;

        if ($headerName === SecurityHeader::SET_COOKIE) {
            $callback = function (array $match): string {
                if (strlen($match['value']) < 20) {
                    return $match['all'];
                }

                return sprintf(
                    '%s=s%s[<bg=cyan>...</>]%s',
                    $match['name'],
                    substr($match['value'], 0, 8),
                    substr($match['value'], -8)
                );
            };

            return preg_replace_callback('~^(?<all>(?<name>.*?)=(?<value>.*?;))~', $callback, $headerValue);
        }

        return $headerValue;
    }

    private function doesFail(HeaderWithObservations $header): bool
    {
        $penalty = 0;

        foreach ($header->getObservations() as $observation) {
            $penalty += $observation->getPenalty();
        }

        return $penalty > 0;
    }
}
