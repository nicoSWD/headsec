<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\SetCookieResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class SetCookieHeader extends AbstractHeaderValidator
{
    private const FLAG_SECURE = 'secure';
    private const FLAG_HTTP_ONLY = 'httponly';
    private const FLAG_SAME_SITE_STRICT = 'samesite=strict';

    public function audit(): AbstractHeaderAuditResult
    {
        $flags = $this->getCookieFlags();

        return (new SetCookieResult($this->getName(), $this->getValue()))
            ->setCookieName($this->getCookieName())
            ->setHasFlagHttpOnly($this->hasHttpOnlyFlag($flags))
            ->setHasFlagSecure($this->hasSecureFlag($flags))
            ->setHasFlagSameSite($this->hasSameSiteFlag($flags));
    }

    private function hasSecureFlag(array $options): bool
    {
        return in_array(self::FLAG_SECURE, $options, true);
    }

    private function hasHttpOnlyFlag(array $options): bool
    {
        return in_array(self::FLAG_HTTP_ONLY, $options, true);
    }

    private function hasSameSiteFlag(array $options): bool
    {
        return in_array(self::FLAG_SAME_SITE_STRICT, $options, true);
    }

    private function getCookieFlags(): array
    {
        $callback = function (string $value): string {
            return strtolower(trim($value));
        };

        return array_map($callback, explode(';', $this->getValue()));
    }

    private function getCookieName(): string
    {
        parse_str(explode(';', $this->getValue(), 2)[0], $components);

        return key($components) ?? '';
    }
}
