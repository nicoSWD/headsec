<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

class GenericHeaderAuditResult
{
    /** @var EvaluatedHeader */
    protected $headerValidator;

    public function __construct(EvaluatedHeader $headerValidator)
    {
        $this->headerValidator = $headerValidator;
    }

    public function getEvaluatedHeader(): EvaluatedHeader
    {
        return $this->headerValidator;
    }

    public function name(): string
    {
        return $this->headerValidator->name();
    }

    public function hasWarnings(): bool
    {
        return count($this->headerValidator->warnings()) > 0;
    }
}
