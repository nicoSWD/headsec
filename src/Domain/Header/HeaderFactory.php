<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

final class HeaderFactory
{
    private const MANDATORY = true;
    private const OPTIONAL = false;

    public function createMandatory(string $name): Header
    {
        return new Header($name, self::MANDATORY);
    }

    public function createOptional(string $name): Header
    {
        return new Header($name, self::OPTIONAL);
    }
}
