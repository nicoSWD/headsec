<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XFrameOptionsWithInsecureValueWarning;

final class XFrameOptionsProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $xFrameOptionsResult = $parsedHeaders->getXFrameOptionsResult();
        $observations = [];

        if ($xFrameOptionsResult && !$xFrameOptionsResult->getHasSecureOrigin() && !$xFrameOptionsResult->hasAllowFrom()) {
            $observations[] = new XFrameOptionsWithInsecureValueWarning();
        }

        $auditionResult->addResult($this->getHeaderName(), $this->getHeaderValue(), $observations);
    }
}
