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

    public function getScore(): float
    {
        [$status, $mode] = explode(';', strtolower($this->getUniqueValue()));

        if ($this->protectionIsOn($status)) {
            if ($this->isBlocking($mode)) {
                $score = 1;
            } else {
                $this->addRecommendation('mode=block should be specified');
                $score = .5;
            }
        } else {
            $this->addRecommendation('value should be set to 1');
            $score = 0;
        }

        return $score;
    }

    private function protectionIsOn(?string $status): bool
    {
        return trim($status) === self::MODE_ON;
    }

    private function isBlocking(?string $mode): bool
    {
        return trim($mode, '; ') === 'mode=block';
    }
}
