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
    /** @var SecurityHeader */
    private $securityHeader;

    public function __construct(SecurityHeader $securityHeader)
    {
        $this->securityHeader = $securityHeader;
    }

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

        $excludeFromSuggestions = [
            SecurityHeader::SERVER,
            SecurityHeader::SET_COOKIE,
            SecurityHeader::X_POWERED_BY,
            SecurityHeader::EXPECT_CT
        ];

        return array_diff($this->securityHeader->all(), $foundHeaders, $excludeFromSuggestions);
    }

    public function getScore(): int
    {
        return 9;
    }
}
