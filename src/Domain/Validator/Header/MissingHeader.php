<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ValidationError;

final class MissingHeader extends AbstractHeaderValidator
{
    public function getScore(): float
    {
        $this->addWarning(ValidationError::HEADER_MISSING);

        return .0;
    }
}
