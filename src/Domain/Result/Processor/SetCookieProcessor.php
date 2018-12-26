<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\SetCookieHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithHttpOnlyFlagKudos;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingHttpOnlyFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSameSiteFlagInfo;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSecureFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithSecureFlagKudos;

final class SetCookieProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if (!$this->header()->hasFlagHttpOnly()) {
            $observations->attach(new CookieWithMissingHttpOnlyFlagWarning());
        } else {
            $observations->attach(new CookieWithHttpOnlyFlagKudos());
        }

        if (!$this->header()->hasFlagSecure()) {
            $observations->attach(new CookieWithMissingSecureFlagWarning());
        } else {
            $observations->attach(new CookieWithSecureFlagKudos());
        }

        if (!$this->header()->hasFlagSameSite()) {
            $observations->attach(new CookieWithMissingSameSiteFlagInfo());
        }

        $this->addObservations($observations);
    }

    private function header(): SetCookieHeaderResult
    {
        return $this->parsedHeader;
    }
}
