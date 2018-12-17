<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ReferrerPolicyHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class ReferrerPolicyHeader extends AbstractHeaderValidator
{
    public function audit(): AbstractHeaderAuditResult
    {
        $referrerPolicyResult = new ReferrerPolicyHeaderResult($this->getName());
        $referrerPolicyResult->setMayLeakOrigin($this->mayLeakOrigin());
        $referrerPolicyResult->setDoesNotLeakReferrer($this->doesNotLeakReferrer());

        return $referrerPolicyResult;
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
