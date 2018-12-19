<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\OutputOptions;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class ConsoleResultPrinter implements ResultPrinterInterface
{
    public function getOutput(AuditionResult $scanResults, OutputOptions $outputOptions): string
    {
        $maxHeaderLength = $this->getHeaderMaxLength($scanResults);

        $output = '';
        $totalWarnings = 0;

        $allObservations = $scanResults->getObservations();
        $padding = strlen(sprintf('%s', count($allObservations)));

        foreach ($allObservations as [$headerName, $headerValue, $observations]) {
            if (!$observations && !$outputOptions->showAllHeaders()) {
                continue;
            }

            $totalWarnings++;

            $output .= sprintf("(%{$padding}s)", $totalWarnings) . ' <bg=default;fg=white>' . str_pad($this->prettyName($headerName) . ': ' . $this->shortenHeaderValue($headerName, $headerValue),
                    $maxHeaderLength, ' ') . ' </>' . $this->getWarnings($observations) . PHP_EOL ;
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
        return implode('-', array_map('ucfirst', explode('-', $headerName)));
    }

    private function getWarnings(array $observations): string
    {
        $out = PHP_EOL . '     ';

        foreach ($observations as $observation) {
            $out .= '<bg=red;fg=black> ' . (string) $observation . ' </> ';
        }

        if (empty($observations)) {
            $out .= '<bg=green;fg=black>No issues</> ';
        }

        return $out;
    }

    private function getHeaderMaxLength(AuditionResult $scanResults): int
    {
        $maxHeaderLength = 0;

        foreach ($scanResults->getObservations() as [$headerName]) {
            $length = strlen($headerName);
            if ($length > $maxHeaderLength) {
                $maxHeaderLength = $length;
            }
        }

        return $maxHeaderLength;
    }

    private function shortenHeaderValue(string $headerName, string $headerValue): string
    {
        $width = (int) `tput cols`;

        if ($headerName === SecurityHeader::SET_COOKIE) {
            $callback = function (array $match): string {
                if  (strlen($match['value']) < 20) {
                    return $match['name'] . $match['value'];
                }

                return sprintf(
                    '%s%s[...]%s',
                    $match['name'],
                    substr($match['value'], 0, 8),
                    substr($match['value'], -8)
                );
            };

            return preg_replace_callback('~^(?<name>.*?=)(?<value>.*?;)~', $callback, $headerValue);
        }

        return $headerValue;
    }
}
