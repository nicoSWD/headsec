<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\ResultPrinter;

final class OutputOptions
{
    private $showAllHeaders = false;

    public function showAllHeaders(): bool
    {
        return $this->showAllHeaders;
    }

    public function setShowAllHeaders(bool $showAllHeaders): void
    {
        $this->showAllHeaders = $showAllHeaders;
    }
}
