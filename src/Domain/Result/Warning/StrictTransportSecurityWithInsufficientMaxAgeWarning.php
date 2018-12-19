<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Warning;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning;

final class StrictTransportSecurityWithInsufficientMaxAgeWarning extends Warning
{
    protected $message = 'Recommended max-age is \'31536000\' (one year)';
}
