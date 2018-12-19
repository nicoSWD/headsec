<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XFrameOptionsWithInsecureValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XFrameOptionsHeaderResult;

final class XFrameOptionsProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders): void
    {
        $observations = [];

        if (!$this->header()->getHasSecureOrigin() && !$this->header()->hasAllowFrom()) {
            $observations[] = new XFrameOptionsWithInsecureValueWarning();
        }

        $this->addResult($observations);
    }

    private function header(): XFrameOptionsHeaderResult
    {
        return $this->parsedHeader;
    }
}
