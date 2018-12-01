<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\AbstractHeaderProvider;

final class NativeAbstractHeaderProvider extends AbstractHeaderProvider
{
    protected function getHeaders(string $url): array
    {
        $headers = @get_headers($url, 1);

        if ($headers === false) {
            throw new \Exception('Unable to fetch headers');
        }

        return $headers;
    }
}
