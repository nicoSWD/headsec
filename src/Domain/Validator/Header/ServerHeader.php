<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ServerResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class ServerHeader extends AbstractHeaderValidator
{
    public function audit(): AbstractHeaderAuditResult
    {
        return (new ServerResult($this->getName(), $this->getValue()))
            ->setLeaksServerVersion($this->serverContainsVersionNumber());
    }

    private function serverContainsVersionNumber(): bool
    {
        return preg_match('~\d+\.\d+~', $this->getValue()) === 1;
    }
}
