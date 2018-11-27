<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Infrastructure\Header;

use nicoSWD\SecHeaderCheck\Domain\Headers\HeaderProviderInterface;

final class NativeHeaderProviderInterface implements HeaderProviderInterface
{
    public function getHeaders(string $url): array
    {
        $headers = @get_headers($url, 1);

        if ($headers === false) {
            throw new \Exception('Unable to fetch headers');
        }

        return $headers;
    }
}
