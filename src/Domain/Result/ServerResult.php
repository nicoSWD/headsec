<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

final class ServerResult extends AbstractParsedHeader
{
    private $leaksServerVersion = true;

    public function isSecure(): bool
    {
        return !$this->leaksServerVersion();
    }

    public function leaksServerVersion(): bool
    {
        return $this->leaksServerVersion;
    }

    public function setLeaksServerVersion(bool $leaksServerVersion): self
    {
        $this->leaksServerVersion = $leaksServerVersion;

        return $this;
    }
}
