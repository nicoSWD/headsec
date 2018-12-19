<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\XContentTypeOptionsHeader;
use PHPUnit\Framework\TestCase;

final class XContentTypeOptionsHeaderTest extends TestCase
{
    public function testGivenAPerfectXContentTypeOptionsHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new XContentTypeOptionsHeader('nosniff');

        $this->assertSame(1., $header->parse());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenAnXContentTypeHeaderWithAnInvalidValueItShouldWarnAndPenalise()
    {
        $header = new XContentTypeOptionsHeader('anything');

        $this->assertSame(.0, $header->parse());
        $this->assertCount(1, $header->getWarnings());
    }
}
