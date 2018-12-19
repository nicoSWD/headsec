<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XContentTypeWithInvalidValueWarning;

final class XContentTypeOptionsProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $xContentTypeOptionsResult = $parsedHeaders->getXContentTypeOptionsResult();
        $observations = [];

        if ($xContentTypeOptionsResult && !$xContentTypeOptionsResult->isNoSniff()) {
            $observations[] = new XContentTypeWithInvalidValueWarning();
        }

        $auditionResult->addResult($this->getHeaderName(), $this->getHeaderValue(), $observations);
    }
}
