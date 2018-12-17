<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Warning;

final class StrictTransportSecurityWithInsufficientMaxAgeWarning extends Warning
{
    protected $message = 'Insufficient max-age \'%d\'. Recommended value is \'31536000\' seconds (one year)';
}
