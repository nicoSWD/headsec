<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class ReferrerPolicyHeader extends AbstractHeaderValidator
{
    public function getScore(): float
    {
        $value = strtolower($this->getUniqueValue());

        if ($this->doesNotLeakReferrer($value)) {
            return 1;
        }

        if ($this->mayLeakOrigin($value)) {
            $this->addWarning("Option '{$value}' may leak partial referrer information");
            return .5;
        }

        return .0;
    }

    private function doesNotLeakReferrer(string $value): bool
    {
        $secureReferrerOptions = [
            'no-referrer',
            'no-referrer-when-downgrade',
            'same-origin',
            'strict-origin',
        ];

        return in_array($value, $secureReferrerOptions, true);
    }

    private function mayLeakOrigin(string $value): bool
    {
        $leakyReferrerOptions = [
            'origin',
            'origin-when-cross-origin',
            'strict-origin-when-cross-origin',
        ];

        return in_array($value, $leakyReferrerOptions, true);
    }
}
