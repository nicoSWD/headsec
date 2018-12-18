<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class AuditionResult
{
    private $observations = [];
    private $missingHeaders = [];

    public function getObservations(): array
    {
        return $this->observations;
    }

    public function addObservation(string $headerName, string $headerValue, $observation)
    {
        if (!empty($observation)) {
            $this->observations[] = [$headerName, $headerValue, $observation];
        }
    }

    public function addMissingHeader(string $missingHeader): void
    {
        $this->missingHeaders[] = $missingHeader;
    }

    public function getMissingHeaders(): array
    {
        return $this->missingHeaders;
    }

    public function getScore(): int
    {
        return 9;
    }
}
