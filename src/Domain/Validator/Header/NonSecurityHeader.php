<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class NonSecurityHeader extends SecurityHeader
{
    public function getScore(): float
    {
        return .0;
    }
}
