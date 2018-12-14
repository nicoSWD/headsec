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

final class ReferrerPolicyHeader extends AbstractHeaderValidator
{
    protected function scan(): void
    {
        $policy = strtolower($this->getValue());

        if ($this->doesNotLeakReferrer($policy)) {
            // Good job
        } elseif ($this->mayLeakOrigin($policy)) {
            $this->addWarning(
                ErrorSeverity::MEDIUM,
                ValidationError::OPTION_MAY_LEAK_PARTIAL_REFERRER_INFO,
                [$policy]
            );
        } else {
            $this->addWarning(ErrorSeverity::VERY_HIGH, ValidationError::INVALID_REFERRER_POLICY);
        }
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
