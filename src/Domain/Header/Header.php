<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

final class Header
{
    private $name = '';
    private $isMandatory = true;

    public function __construct(string $name, bool $isMandatory)
    {
        $this->name = $name;
        $this->isMandatory = $isMandatory;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isMandatory(): bool
    {
        return $this->isMandatory;
    }
}
