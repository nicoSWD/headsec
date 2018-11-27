<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class XFrameOptions extends SecurityHeader
{
    public function getScore(): float
    {
        $value = strtolower($this->getValue());

        if ($this->isSecureOrigin($value) || $this->hasAllowFrom($value)) {
            return 1;
        }

        return 0;
    }

    private function isSecureOrigin(string $value): bool
    {
        return $value === 'deny' || $value === 'sameorigin';
    }

    private function hasAllowFrom(string $value): bool
    {
        return strpos($value, 'allow-from') === 0;
    }
}
