<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class ReferrerPolicy extends SecurityHeader
{
    public function getScore(): float
    {
        $value = strtolower($this->getValue());

        if ($this->doesNotLeakReferrer($value)) {
            return 1;
        }

        if ($this->mayLeakOrigin($value)) {
            $this->addRecommendation("{$value} may leak partial referrer information");
            return .5;
        }

        return 0;
    }

    private function doesNotLeakReferrer(string $value): bool
    {
        return in_array($value, ['no-referrer', 'no-referrer-when-downgrade', 'same-origin', 'strict-origin'], true);
    }

    private function mayLeakOrigin(string $value): bool
    {
        return in_array($value, ['origin', 'origin-when-cross-origin', 'strict-origin-when-cross-origin'], true);
    }
}
