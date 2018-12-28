<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result\Result;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

final class ReferrerPolicyHeaderResult extends AbstractParsedHeader
{
    private $mayLeakOrigin = true;
    private $doesNotLeakReferrer = false;

    public function isSecure(): bool
    {
        return $this->doesNotLeakReferrer();
    }

    public function mayLeakOrigin(): bool
    {
        return $this->mayLeakOrigin;
    }

    public function setMayLeakOrigin(bool $mayLeakOrigin): self
    {
        $this->mayLeakOrigin = $mayLeakOrigin;

        return $this;
    }

    public function doesNotLeakReferrer(): bool
    {
        return $this->doesNotLeakReferrer;
    }

    public function setDoesNotLeakReferrer(bool $doesNotLeakReferrer): self
    {
        $this->doesNotLeakReferrer = $doesNotLeakReferrer;

        return $this;
    }
}
