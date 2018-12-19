<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;

abstract class AbstractProcessor
{
    private $headerName = '';
    private $headerValue = '';

    public function __construct(string $headerName, string $headerValue)
    {
        $this->headerName = $headerName;
        $this->headerValue = $headerValue;
    }

    abstract public function process(ParsedHeaders $parsedHeaders, AuditionResult $auditionResult): void;

    protected function getHeaderName(): string
    {
        return $this->headerName;
    }

    protected function getHeaderValue(): string
    {
        return $this->headerValue;
    }
}
