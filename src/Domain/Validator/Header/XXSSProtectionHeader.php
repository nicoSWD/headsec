<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ErrorSeverity;

final class XXSSProtectionHeader extends AbstractHeaderValidator
{
    private const MODE_ON = '1';
    private const MODE_BLOCK = 'mode=block';

    protected function scan(): void
    {
        $options = $this->getOptions();

        if ($this->protectionIsOn($options)) {
            if ($this->isBlocking($options)) {
                if (!$this->hasReportUri($options)) {
                    $this->addWarning(ErrorSeverity::NONE, 'Consider adding a report URI');
                }
            } else {
                $this->addWarning(ErrorSeverity::MEDIUM, 'mode=block should be specified');
            }
        } else {
            $this->addWarning(ErrorSeverity::VERY_HIGH, 'value should be set to 1');
        }
    }

    private function protectionIsOn(array $options): bool
    {
        return in_array(self::MODE_ON, $options, true);
    }

    private function isBlocking(array $options): bool
    {
        return in_array(self::MODE_BLOCK, $options, true);
    }

    private function hasReportUri(array $options): bool
    {
        return count(preg_grep('~report=~', $options)) === 1;
    }

    private function getOptions(): array
    {
        return preg_split('~\s*;\s*~', $this->getUniqueValue(), -1, PREG_SPLIT_NO_EMPTY);
    }
}
