<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithInvalidValueWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\ReferrerPolicyWithLeakingOriginWarning;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class ReferrerPolicyHeader extends AbstractHeaderValidator
{
    protected function scan(): void
    {
        if ($this->mayLeakOrigin()) {
            $this->addWarning(new ReferrerPolicyWithLeakingOriginWarning($this->getValue()));
        } elseif (!$this->doesNotLeakReferrer()) {
            $this->addWarning(new ReferrerPolicyWithInvalidValueWarning($this->getValue()));
        }
    }

    private function doesNotLeakReferrer(): bool
    {
        $secureReferrerOptions = [
            'no-referrer',
            'no-referrer-when-downgrade',
            'same-origin',
            'strict-origin',
        ];

        return $this->valueIsIn($secureReferrerOptions);
    }

    private function mayLeakOrigin(): bool
    {
        $leakyReferrerOptions = [
            'origin',
            'origin-when-cross-origin',
            'strict-origin-when-cross-origin',
        ];

        return $this->valueIsIn($leakyReferrerOptions);
    }

    private function valueIsIn(array $options): bool
    {
        return in_array(strtolower($this->getValue()), $options, true);
    }
}
