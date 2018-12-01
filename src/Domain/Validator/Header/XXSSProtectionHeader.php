<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XXSSProtectionHeader extends AbstractHeaderValidator
{
    private const MODE_ON = '1';
    private const MODE_BLOCK = 'mode=block';

    public function getScore(): float
    {
        $options = $this->getOptions();

        if ($this->protectionIsOn($options)) {
            if ($this->isBlocking($options)) {
                $score = 1;

                if (!$this->hasReportUri($options)) {
                    $this->addWarning('Consider adding a report URI');
                }
            } else {
                $this->addWarning('mode=block should be specified');
                $score = .5;
            }
        } else {
            $this->addWarning('value should be set to 1');
            $score = .0;
        }

        return $score;
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
        return count(preg_grep('~report=~', $options)) > 0;
    }

    private function getOptions(): array
    {
        $options = preg_split('~;~', $this->getUniqueValue(), -1, PREG_SPLIT_NO_EMPTY);

        return array_map('trim', $options);
    }
}
