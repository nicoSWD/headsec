<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\Warning\XPoweredByDisclosesTechnologyWarning;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class XPoweredByHeader extends AbstractHeaderValidator
{
    protected function scan(): void
    {
        $this->addWarning(new XPoweredByDisclosesTechnologyWarning());
    }
}
