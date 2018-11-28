<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class JSONResultPrinter implements ResultPrinterInterface
{
    public function print(float $score, array $recommendations): string
    {
        $data = [
            'score'           => $score,
            'recommendations' => $recommendations,
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
