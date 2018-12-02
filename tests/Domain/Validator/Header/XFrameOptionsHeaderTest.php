<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XFrameOptionsHeader;
use PHPUnit\Framework\TestCase;

final class XFrameOptionsHeaderTest extends TestCase
{
    public function testGivenADenyXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader('deny');

        $this->assertSame(1., $header->getScore());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenASameOriginXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader('sameorigin');

        $this->assertSame(1., $header->getScore());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenAnAllowFromXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader('allow-from https://www.google.com');

        $this->assertSame(1., $header->getScore());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenAnInsecureXFrameOptionsHeaderItShouldWarnAboutIt()
    {
        $header = new XFrameOptionsHeader('allow');

        $this->assertSame(0., $header->getScore());
        $this->assertCount(1, $header->getWarnings());
    }
}
