<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ObservationCollection;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\ReferrerPolicyHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithInvalidValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithLeakingOriginWarning;

final class ReferrerPolicyProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = new ObservationCollection();

        if ($this->header()->isMayLeakOrigin()) {
            $observations->attach(new ReferrerPolicyWithLeakingOriginWarning());
        } elseif (!$this->header()->doesNotLeakReferrer()) {
            $observations->attach(new ReferrerPolicyWithInvalidValueWarning());
        }

        $this->addObservations($observations);
    }

    private function header(): ReferrerPolicyHeaderResult
    {
        return $this->parsedHeader;
    }
}
