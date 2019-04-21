<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;

class AuditionResult
{
    /** @var HeaderWithObservations[] */
    private $observations = [];
    /** @var SecurityHeader */
    private $securityHeader;

    public function __construct(SecurityHeader $securityHeader)
    {
        $this->securityHeader = $securityHeader;
    }

    /** @return HeaderWithObservations[] */
    public function getObservations()
    {
        return $this->observations;
    }

    public function addObservations(HeaderWithObservations $observations)
    {
        $this->observations[] = $observations;
    }

    public function getMissingHeaders(): array
    {
        $foundHeaders = [];

        foreach ($this->getObservations() as $observation) {
            $foundHeaders[] = $observation->getHeaderName();
        }

        $excludeFromSuggestions = [
            SecurityHeader::EXPECT_CT,
            SecurityHeader::SERVER,
            SecurityHeader::SET_COOKIE,
            SecurityHeader::X_POWERED_BY,
        ];

        return array_diff($this->securityHeader->all(), $foundHeaders, $excludeFromSuggestions);
    }

    public function getScore(): int
    {
        return 9;
    }
}
