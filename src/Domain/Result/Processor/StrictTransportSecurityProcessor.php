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
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithIncludeSubDomainsFlagKudos;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithInsufficientMaxAgeWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithMissingIncludeSubDomainsFlagInfo;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\StrictTransportSecurityWithSufficientMaxAgeKudos;

final class StrictTransportSecurityProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->hasSecureMaxAge()) {
            $observations->addWarning(new StrictTransportSecurityWithInsufficientMaxAgeWarning());
        } else {
            $observations->addKudos(new StrictTransportSecurityWithSufficientMaxAgeKudos());
        }

        if (!$this->header()->hasFlagIncludeSubDomains()) {
            $observations->addInfo(new StrictTransportSecurityWithMissingIncludeSubDomainsFlagInfo());
        } else {
            $observations->addKudos(new StrictTransportSecurityWithIncludeSubDomainsFlagKudos());
        }

        $this->addObservations($observations);
    }

    private function header(): StrictTransportSecurityHeaderResult
    {
        return $this->parsedHeader;
    }
}
