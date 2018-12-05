<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

final class ErrorSeverity
{
    public const NONE = 0;
    public const VERY_LOW = .2;
    public const LOW = .3;
    public const MEDIUM = .5;
    public const HIGH = .8;
    public const VERY_HIGH = 1;
}
