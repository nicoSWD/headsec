<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\StrictTransportSecurityHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithInsufficientMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning;

final class StrictTransportSecurityProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->hasSecureMaxAge()) {
            $observations->attach(new StrictTransportSecurityWithInsufficientMaxAgeWarning());
        }

        if (!$this->header()->hasFlagIncludeSubDomains()) {
            $observations->attach(new StrictTransportSecurityWithMissingIncludeSubDomainsFlagWarning());
        }

        $this->addObservations($observations);
    }

    private function header(): StrictTransportSecurityHeaderResult
    {
        return $this->parsedHeader;
    }
}
