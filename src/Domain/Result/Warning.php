<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

abstract class Warning extends AbstractObservation
{
    public function getPenalty(): float
    {
        return .5;
    }

    public function isWarning(): bool
    {
        return true;
    }
}
