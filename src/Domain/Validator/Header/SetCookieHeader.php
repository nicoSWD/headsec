<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class SetCookieHeader extends AbstractHeaderValidator
{
    public function getScore(): float
    {
        foreach ($this->getCookies() as $cookie) {
            $options = $this->getCookieOptions($cookie);

            if (!$this->hasSecureFlag($options)) {
                $this->addWarning("Cookies should be set with the <Secure> flag");
            }

            if (!$this->hasHttpOnlyFlag($options)) {
                $this->addWarning("Cookies should be set with the <HttpOnly> flag");
            }

            if (!$this->hasSameSiteFlag($options)) {
                $this->addWarning("Cookies should be set with the <SameSite> flag to prevent CSRF");
            }
        }

        return .0;
    }

    private function getCookieOptions(string $cookie): array
    {
        $callback = function (string $value): string {
            return strtolower(trim($value));
        };

        return array_map($callback, explode(';', $cookie));
    }

    private function getCookies(): array
    {
        return (array) $this->getValue();
    }

    private function hasSecureFlag(array $options): bool
    {
        return in_array('secure', $options, true);
    }

    private function hasHttpOnlyFlag(array $options): bool
    {
        return in_array('httponly', $options, true);
    }

    private function hasSameSiteFlag(array $options): bool
    {
        return in_array('samesite=strict', $options, true);
    }
}
