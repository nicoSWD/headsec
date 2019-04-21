<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\StrictTransportSecurityHeader;
use PHPUnit\Framework\TestCase;

final class StrictTransportSecurityHeaderTest extends TestCase
{
    public function testGivenAPerfectStrictTransportSecurityHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new StrictTransportSecurityHeader(new HttpHeader(SecurityHeader::STRICT_TRANSPORT_SECURITY, 'max-age=31536000; includeSubdomains'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasSecureMaxAge());
        $this->assertTrue($parsedHeader->hasFlagIncludeSubDomains());
    }

    public function testGivenAStrictTransportSecurityHeaderWithMissingSubDomainsFlagItShouldWarnAboutItButNotPenalise()
    {
        $header = new StrictTransportSecurityHeader(new HttpHeader(SecurityHeader::STRICT_TRANSPORT_SECURITY, 'max-age=15768000'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasSecureMaxAge());
        $this->assertFalse($parsedHeader->hasFlagIncludeSubDomains());
    }

    public function testGivenAStrictTransportSecurityHeaderWithMissingMaxAgeItShouldWarnAndPenalise()
    {
        $header = new StrictTransportSecurityHeader(new HttpHeader(SecurityHeader::STRICT_TRANSPORT_SECURITY, 'includeSubdomains'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasSecureMaxAge());
        $this->assertTrue($parsedHeader->hasFlagIncludeSubDomains());
    }
}
