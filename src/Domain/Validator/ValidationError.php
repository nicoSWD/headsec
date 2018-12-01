<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

final class ValidationError
{
    public const HEADER_MISSING = 'Header is missing';
    public const HEADER_DUPLICATE = 'Header has been sent multiple times';
    public const SERVER_VERSION_DISCLOSURE = 'Server version may be leaking';
    public const CSP_DIRECTIVE_MISSING = 'Missing directive';
}
