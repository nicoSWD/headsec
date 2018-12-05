<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XXSSProtectionHeader;
use PHPUnit\Framework\TestCase;

final class XXSSProtectionHeaderTest extends TestCase
{
    public function testGivenAPerfectHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XXSSProtectionHeader('1; mode=block; report=/xss/report');

        $this->assertSame(1., $header->scan());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenAGoodHeaderWithoutReportUriItShouldWarnAboutIt()
    {
        $header = new XXSSProtectionHeader('1; mode=block');

        $this->assertSame(1., $header->scan());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenAHeaderWhenProtectionIsOnItShouldWarnAboutMissingBlockMode()
    {
        $header = new XXSSProtectionHeader('1');

        $this->assertSame(.5, $header->scan());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenAHeaderWhenProtectionIsOffItShouldWarnAboutIt()
    {
        $header = new XXSSProtectionHeader('0');

        $this->assertSame(.0, $header->scan());
        $this->assertCount(1, $header->getWarnings());
    }
}
