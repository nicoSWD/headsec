<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

final class HeaderBag
{
    private $headers = [];

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function has(string $headerName): bool
    {
        return array_key_exists($headerName, $this->headers);
    }

    public function get(string $headerName)
    {
        return $this->headers[$headerName];
    }
}
