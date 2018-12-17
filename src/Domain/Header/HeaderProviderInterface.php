<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\URL\URL;

interface HeaderProviderInterface
{
    public function getHeadersFromUrl(URL $url, bool $followRedirects = true): HttpHeaderBag;
}
