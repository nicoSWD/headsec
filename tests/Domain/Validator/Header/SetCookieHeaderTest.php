<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\SetCookieHeader;
use PHPUnit\Framework\TestCase;

final class SetCookieHeaderTest extends TestCase
{
    public function testGivenAPerfectSetCookieHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new SetCookieHeader(new HttpHeader(SecurityHeader::SET_COOKIE, 'foo=bar;Secure;HttpOnly;SameSite=strict'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasFlagSameSite());
        $this->assertTrue($parsedHeader->hasFlagHttpOnly());
        $this->assertTrue($parsedHeader->hasFlagSecure());
        $this->assertSame('foo', $parsedHeader->cookieName());
    }

    public function testGivenACookieHeaderWithMissingSecureFlagItShouldWarn()
    {
        $header = new SetCookieHeader(new HttpHeader(SecurityHeader::SET_COOKIE, 'foo=bar;HttpOnly;SameSite=lax'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasFlagSameSite());
        $this->assertTrue($parsedHeader->hasFlagHttpOnly());
        $this->assertFalse($parsedHeader->hasFlagSecure());
        $this->assertSame('foo', $parsedHeader->cookieName());
    }

    public function testGivenACookieHeaderWithMissingHttpOnlyFlagItShouldWarn()
    {
        $header = new SetCookieHeader(new HttpHeader(SecurityHeader::SET_COOKIE, 'foo=bar;Secure;SameSite=strict'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasFlagSameSite());
        $this->assertFalse($parsedHeader->hasFlagHttpOnly());
        $this->assertTrue($parsedHeader->hasFlagSecure());
        $this->assertSame('foo', $parsedHeader->cookieName());
    }

    public function testGivenACookieHeaderWithMissingSameSiteFlagItShouldWarn()
    {
        $header = new SetCookieHeader(new HttpHeader(SecurityHeader::SET_COOKIE, 'foo=bar;Secure;HttpOnly'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->hasFlagSameSite());
        $this->assertTrue($parsedHeader->hasFlagHttpOnly());
        $this->assertTrue($parsedHeader->hasFlagSecure());
        $this->assertSame('foo', $parsedHeader->cookieName());
    }
}
