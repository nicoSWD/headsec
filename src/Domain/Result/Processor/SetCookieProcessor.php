<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingHttpOnlyFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSecureFlagWarning;

final class SetCookieProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        foreach ($parsedHeaders->getSetCookieResult() as $headerResult) {
            $observations = [];

            if (!$headerResult->hasFlagHttpOnly()) {
                $observations[] = new CookieWithMissingHttpOnlyFlagWarning();
            }

            if (!$headerResult->hasFlagSecure()) {
                $observations[] = new CookieWithMissingSecureFlagWarning();
            }

            $auditionResult->addResult($this->getHeaderName(), $this->getHeaderValue(), $observations);
        }
    }
}
