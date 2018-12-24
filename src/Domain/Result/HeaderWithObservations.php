<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class HeaderWithObservations
{
    /** @var string */
    private $headerName;
    /** @var string */
    private $headerValue;
    /** @var ObservationCollection */
    private $observations;

    public function __construct(string $headerName, string $headerValue, ObservationCollection $observations)
    {
        $this->headerName = $headerName;
        $this->headerValue = $headerValue;
        $this->observations = $observations;
    }

    public function getHeaderName(): string
    {
        return $this->headerName;
    }

    public function getHeaderValue(): string
    {
        return $this->headerValue;
    }

    public function getObservations(): ObservationCollection
    {
        return $this->observations;
    }
}
