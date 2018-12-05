<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ErrorSeverity;

final class XContentTypeOptionsHeader extends AbstractHeaderValidator
{
    private const NO_SNIFF = 'nosniff';

    protected function scan(): void
    {
        if (!$this->isNoSniff()) {
            $this->addWarning(ErrorSeverity::VERY_HIGH, 'nosniff is the expected value');
        }
    }

    private function isNoSniff(): bool
    {
        return strtolower($this->getUniqueValue()) === self::NO_SNIFF;
    }
}
