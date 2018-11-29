<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;

final class XContentTypeOptions extends SecurityHeader
{
    private const NO_SNIFF = 'nosniff';

    public function getScore(): float
    {
        $value = $this->getUniqueValue();

        if (!$this->isNoSniff($value)) {
            $this->addWarning('"nosniff" is the expected value');
            return .0;
        }

        return 1;
    }

    private function isNoSniff(string $value): bool
    {
        return strtolower($value) === self::NO_SNIFF;
    }
}
