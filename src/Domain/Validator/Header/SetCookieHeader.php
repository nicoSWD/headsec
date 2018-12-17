<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingHttpOnlyFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSameSiteFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Result\Warning\CookieWithMissingSecureFlagWarning;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class SetCookieHeader extends AbstractHeaderValidator
{
    private const FLAG_SECURE = 'secure';
    private const FLAG_HTTP_ONLY = 'httponly';
    private const FLAG_SAME_SITE_STRICT = 'samesite=strict';

    protected function scan(): void
    {
        $flags = $this->getCookieFlags($this->getValue());

        if (!$this->hasSecureFlag($flags)) {
            $this->addWarning(new CookieWithMissingSecureFlagWarning($this->getCookieName()));
        }

        if (!$this->hasHttpOnlyFlag($flags)) {
            $this->addWarning(new CookieWithMissingHttpOnlyFlagWarning($this->getCookieName()));
        }

        if (!$this->hasSameSiteFlag($flags)) {
            $this->addWarning(new CookieWithMissingSameSiteFlagWarning($this->getCookieName()));
        }
    }

    private function getCookieFlags(string $cookie): array
    {
        $callback = function (string $value): string {
            return strtolower(trim($value));
        };

        return array_map($callback, explode(';', $cookie));
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

    private function getCookieName(): string
    {
        parse_str(explode(';', $this->getValue(), 2)[0], $components);

        return key($components) ?? '';
    }
}
