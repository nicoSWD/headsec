<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XFrameOptionsHeader;
use PHPUnit\Framework\TestCase;

final class XFrameOptionsHeaderTest extends TestCase
{
    public function testGivenADenyXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader(new HttpHeader(SecurityHeader::X_FRAME_OPTIONS, 'deny'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->getHasSecureOrigin());
    }

    public function testGivenASameOriginXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader(new HttpHeader(SecurityHeader::X_FRAME_OPTIONS, 'sameorigin'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->getHasSecureOrigin());
    }

    public function testGivenAnAllowFromXFrameOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XFrameOptionsHeader(new HttpHeader(SecurityHeader::X_FRAME_OPTIONS, 'allow-from https://www.google.com'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->hasAllowFrom());
    }

    public function testGivenAnInsecureXFrameOptionsHeaderItShouldWarnAboutIt()
    {
        $header = new XFrameOptionsHeader(new HttpHeader(SecurityHeader::X_FRAME_OPTIONS, 'allow'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->getHasSecureOrigin());
    }
}
