<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Result;

use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\ContentSecurityPolicyHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\XFrameOptionsHeaderResult;

final class ParsedHeaders
{
    /** @var ParsedHeaderBag */
    private $headers;

    public function __construct()
    {
        $this->headers = new ParsedHeaderBag();
    }

    public function add(AbstractParsedHeader $header): void
    {
        $this->headers->add($header);
    }

    public function all(): ParsedHeaderBag
    {
        return $this->headers;
    }

    /** @return ContentSecurityPolicyHeaderResult[] */
    public function getContentSecurityPolicyResult()
    {
        return $this->headers->findMultiple(SecurityHeader::CONTENT_SECURITY_POLICY);
    }

    public function getXFrameOptionsResult(): ?XFrameOptionsHeaderResult
    {
        return $this->headers->findOne(SecurityHeader::X_FRAME_OPTIONS);
    }
}
