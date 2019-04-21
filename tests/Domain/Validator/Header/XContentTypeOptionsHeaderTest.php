<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XContentTypeOptionsHeader;
use PHPUnit\Framework\TestCase;

final class XContentTypeOptionsHeaderTest extends TestCase
{
    public function testGivenAPerfectXContentTypeOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XContentTypeOptionsHeader(new HttpHeader(SecurityHeader::X_CONTENT_TYPE_OPTIONS, 'nosniff'));
        $parsedHeader = $header->parse();

        $this->assertTrue($parsedHeader->isNoSniff());
    }

    public function testGivenAnXContentTypeHeaderWithAnInvalidValueItShouldWarnAndPenalise()
    {
        $header = new XContentTypeOptionsHeader(new HttpHeader(SecurityHeader::X_CONTENT_TYPE_OPTIONS, 'anything'));
        $parsedHeader = $header->parse();

        $this->assertFalse($parsedHeader->isNoSniff());
    }
}
