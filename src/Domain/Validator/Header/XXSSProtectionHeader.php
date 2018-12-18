<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\XXSSProtectionHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XXSSProtectionHeader extends AbstractHeaderValidator
{
    private const MODE_ON = '1';
    private const MODE_BLOCK = 'mode=block';

    public function audit(): AbstractHeaderAuditResult
    {
        $options = $this->getOptions();

        return (new XXSSProtectionHeaderResult($this->getName(), $this->getValue()))
            ->setProtectionIsOn($this->protectionIsOn($options))
            ->setIsBlocking($this->isBlocking($options))
            ->setHasReportUri($this->hasReportUri($options));
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
        return preg_split('~\s*;\s*~', $this->getValue(), -1, PREG_SPLIT_NO_EMPTY);
    }
}
