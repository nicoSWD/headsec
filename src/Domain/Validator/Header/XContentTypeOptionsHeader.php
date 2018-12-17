<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\XContentTypeOptionsHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XContentTypeOptionsHeader extends AbstractHeaderValidator
{
    private const NO_SNIFF = 'nosniff';

    public function audit(): AbstractHeaderAuditResult
    {
        $XContentTypeOptionsHeaderResult = new XContentTypeOptionsHeaderResult($this->getName());
        $XContentTypeOptionsHeaderResult->setIsNoSniff($this->isNoSniff());

        return $XContentTypeOptionsHeaderResult;
    }

    private function isNoSniff(): bool
    {
        return strtolower($this->getValue()) === self::NO_SNIFF;
    }
}
