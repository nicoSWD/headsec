<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Processor;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;

abstract class AbstractProcessor
{
    /** @var AbstractParsedHeader */
    protected $parsedHeader;
    /** @var AuditionResult */
    private $auditionResult;

    abstract public function process(ParsedHeaders $parsedHeaders): void;

    public function __construct(AbstractParsedHeader $parsedHeader, AuditionResult $auditionResult)
    {
        $this->parsedHeader = $parsedHeader;
        $this->auditionResult = $auditionResult;
    }

    protected function addResult(array $observations): void
    {
        $this->auditionResult->addResult($this->parsedHeader->name(), $this->parsedHeader->value(), $observations);
    }
}
