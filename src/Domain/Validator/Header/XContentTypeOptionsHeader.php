<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XContentTypeOptionsHeader extends AbstractHeaderValidator
{
    private const NO_SNIFF = 'nosniff';

    public function getScore(): float
    {
        $value = $this->getUniqueValue();

        if (!$this->isNoSniff($value)) {
            $this->addWarning('"nosniff" is the expected value');
            return self::FAIL;
        }

        return self::PASS;
    }

    private function isNoSniff(string $value): bool
    {
        return strtolower($value) === self::NO_SNIFF;
    }
}
