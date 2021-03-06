<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders;

final class ScanHeadersRequest
{
    public $headers = '';
    public $outputFormat = 'console';
    public $targetScore = 8.;
    public $showAllHeaders = false;
}
