<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class XXSSProtection extends SecurityHeader
{
    private const MODE_ON = '1';
    private const MODE_BLOCK = 'mode=block';

    public function getScore(): float
    {
        [$status, $mode] = $this->getStatusAndMode();

        if ($this->protectionIsOn($status)) {
            if ($this->isBlocking($mode)) {
                $score = 1;
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

    private function protectionIsOn(string $status): bool
    {
        return $status === self::MODE_ON;
    }

    private function isBlocking(string $mode): bool
    {
        return $mode === self::MODE_BLOCK;
    }

    private function getStatusAndMode(): array
    {
        [$status, $mode] = preg_split('~;~', $this->getUniqueValue(), 2, PREG_SPLIT_NO_EMPTY);

        return [trim($status), strtolower(trim($mode))];
    }
}
