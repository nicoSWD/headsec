<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ErrorSeverity;
use nicoSWD\SecHeaderCheck\Domain\Validator\ValidationError;

final class ServerHeader extends AbstractHeaderValidator
{
    protected function scan(): void
    {
        $server = $this->getUniqueValue();

        if ($this->serverContainsVersionNumber($server)) {
            $this->addWarning(ErrorSeverity::LOW, ValidationError::SERVER_VERSION_DISCLOSURE);
        }
    }

    private function serverContainsVersionNumber(string $server): bool
    {
        return preg_match('~\d\.\d~', $server) === 1;
    }
}
