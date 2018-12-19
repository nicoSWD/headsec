<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ServerDisclosedVersionNumberWarning;

final class ServerProcessor extends AbstractProcessor
{
    public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void
    {
        $serverHeader = $parsedHeaders->getServerResult();
        $observations = [];

        if ($serverHeader && $serverHeader->leaksServerVersion()) {
            $observations[] = new ServerDisclosedVersionNumberWarning();
        }

        $auditionResult->addResult($this->getHeaderName(), $this->getHeaderValue(), $observations);
    }
}
