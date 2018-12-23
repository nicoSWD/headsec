<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\SetCookieHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithHttpOnlyFlagInfo;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingHttpOnlyFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSameSiteFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSecureFlagWarning;

final class SetCookieProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = [];

        if (!$this->header()->hasFlagHttpOnly()) {
            $observations[] = new CookieWithMissingHttpOnlyFlagWarning();
        } else {
            $observations[] = new CookieWithHttpOnlyFlagInfo();
        }

        if (!$this->header()->hasFlagSecure()) {
            $observations[] = new CookieWithMissingSecureFlagWarning();
        }

        if (!$this->header()->hasFlagSameSite()) {
            $observations[] = new CookieWithMissingSameSiteFlagWarning();
        }

        $this->addResult($observations);
    }

    private function header(): SetCookieHeaderResult
    {
        return $this->parsedHeader;
    }
}
