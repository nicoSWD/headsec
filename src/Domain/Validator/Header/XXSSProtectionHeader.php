<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\XXSSProtectionHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class XXSSProtectionHeader extends AbstractHeaderParser
{
    private const MODE_ON = '1';
    private const MODE_BLOCK = 'mode=block';

    public function parse(): AbstractParsedHeader
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
