<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ScanResult
{
    /** @var EvaluatedHeader[] */
    private $headers;

    public function addHeader(EvaluatedHeader $header)
    {
        $this->headers[$header->name()] = $header;
    }

    /** @return EvaluatedHeader[] */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getScore(): float
    {
        $score = 0;

        foreach ($this->headers as $header) {
            $score += $header->score();
        }

        return $score;
    }
}
