<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\ReferrerPolicyHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class ReferrerPolicyHeader extends AbstractHeaderParser
{
    public function parse(): AbstractParsedHeader
    {
        return (new ReferrerPolicyHeaderResult($this->getName(), $this->getValue()))
            ->setMayLeakOrigin($this->mayLeakOrigin())
            ->setDoesNotLeakReferrer($this->doesNotLeakReferrer());
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
