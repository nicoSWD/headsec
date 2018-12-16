<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

class GenericHeaderAuditResult
{
    /** @var AbstractHeaderValidator */
    protected $headerValidator;

    public function __construct(EvaluatedHeader $headerValidator)
    {
        $this->headerValidator = $headerValidator;
    }

    public function getEvaluatedHeader(): EvaluatedHeader
    {
        return $this->headerValidator;
    }
}
