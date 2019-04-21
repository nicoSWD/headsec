<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XXSSProtectionHeader;
use PHPUnit\Framework\TestCase;

final class XXSSProtectionHeaderTest extends TestCase
{
    public function testGivenAPerfectHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XXSSProtectionHeader(New HttpHeader(SecurityHeader::X_XSS_PROTECTION, '1; mode=block; report=/xss/report'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasReportUri());
        $this->assertTrue($parsedHeader->isBlocking());
        $this->assertTrue($parsedHeader->protectionIsOn());
    }

    public function testGivenAGoodHeaderWithoutReportUriItShouldWarnAboutIt()
    {
        $header = new XXSSProtectionHeader(New HttpHeader(SecurityHeader::X_XSS_PROTECTION, '1; mode=block'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasReportUri());
        $this->assertTrue($parsedHeader->isBlocking());
        $this->assertTrue($parsedHeader->protectionIsOn());
    }

    public function testGivenAHeaderWhenProtectionIsOnItShouldWarnAboutMissingBlockMode()
    {
        $header = new XXSSProtectionHeader(New HttpHeader(SecurityHeader::X_XSS_PROTECTION, '1'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasReportUri());
        $this->assertFalse($parsedHeader->isBlocking());
        $this->assertTrue($parsedHeader->protectionIsOn());
    }

    public function testGivenAHeaderWhenProtectionIsOffItShouldWarnAboutIt()
    {
        $header = new XXSSProtectionHeader(New HttpHeader(SecurityHeader::X_XSS_PROTECTION, '0'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasReportUri());
        $this->assertFalse($parsedHeader->isBlocking());
        $this->assertFalse($parsedHeader->protectionIsOn());
    }
}
