<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ValidationError;

final class ServerHeader extends AbstractHeaderValidator
{
    public function getScore(): float
    {
        $server = $this->getUniqueValue();

        if (preg_match('~\d\.\d~', $server)) {
            $this->addWarning(ValidationError::SERVER_VERSION_DISCLOSURE);
        }

        return .0;
    }
}
