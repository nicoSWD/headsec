<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XContentTypeWithInvalidValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XContentTypeOptionsHeaderResult;

final class XContentTypeOptionsProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = [];

        if (!$this->header()->isNoSniff()) {
            $observations[] = new XContentTypeWithInvalidValueWarning();
        }

        $this->addResult($observations);
    }

    private function header(): XContentTypeOptionsHeaderResult
    {
        return $this->parsedHeader;
    }
}