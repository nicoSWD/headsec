<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\SetCookieHeader;
use PHPUnit\Framework\TestCase;

final class SetCookieHeaderTest extends TestCase
{
    public function testGivenAPerfectSetCookieHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new SetCookieHeader('foo=bar;Secure;HttpOnly;SameSite=strict');

        $this->assertSame(.0, $header->audit());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenACookieHeaderWithMissingSecureFlagItShouldWarn()
    {
        $header = new SetCookieHeader('foo=bar;HttpOnly;SameSite=strict');

        $this->assertSame(.0, $header->audit());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenACookieHeaderWithMissingHttpOnlyFlagItShouldWarn()
    {
        $header = new SetCookieHeader('foo=bar;Secure;SameSite=strict');

        $this->assertSame(.0, $header->audit());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenACookieHeaderWithMissingSameSiteFlagItShouldWarn()
    {
        $header = new SetCookieHeader('foo=bar;Secure;HttpOnly');

        $this->assertSame(.0, $header->audit());
        $this->assertCount(1, $header->getWarnings());
    }
}
