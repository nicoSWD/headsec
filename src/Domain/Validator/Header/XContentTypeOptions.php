<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class XContentTypeOptions extends SecurityHeader
{
    public function getScore(): float
    {
        $value = strtolower($this->getValue());

        if ($value === 'nosniff') {
            return 1;
        }

        $this->addRecommendation('"nosniff" is the expected value');

        return 0;
    }
}
