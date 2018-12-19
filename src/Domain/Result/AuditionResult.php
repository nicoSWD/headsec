<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

final class AuditionResult
{
    private $observations = [];

    public function getObservations(): array
    {
        return $this->observations;
    }

    public function addResult(string $headerName, string $headerValue, array $observations)
    {
        $this->observations[] = [$headerName, $headerValue, $observations];
    }

    public function getMissingHeaders(): array
    {
        $foundHeaders = [];

        foreach ($this->observations as [$headerName]) {
            $foundHeaders[] = $headerName;
        }

        return array_diff((new SecurityHeader())->all(), $foundHeaders, [SecurityHeader::SERVER, SecurityHeader::SET_COOKIE, SecurityHeader::X_POWERED_BY, SecurityHeader::EXPECT_CT]);
    }

    public function getScore(): int
    {
        return 9;
    }
}
