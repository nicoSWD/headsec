<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\StrictTransportSecurityHeader;
use PHPUnit\Framework\TestCase;

final class StrictTransportSecurityHeaderTest extends TestCase
{
    public function testGivenAPerfectStrictTransportSecurityHeaderItShouldNotReturnAnyWarnings()
    {
        $header = new StrictTransportSecurityHeader('max-age=15768000; includeSubdomains');

        $this->assertSame(1., $header->parse());
        $this->assertEmpty($header->getWarnings());
    }

    public function testGivenAStrictTransportSecurityHeaderWithMissingSubDomainsFlagItShouldWarnAboutItButNotPenalise()
    {
        $header = new StrictTransportSecurityHeader('max-age=15768000');

        $this->assertSame(1., $header->parse());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenAStrictTransportSecurityHeaderWithLowMaxAgeItShouldWarnAndPenalise()
    {
        $header = new StrictTransportSecurityHeader('max-age=5000');

        $this->assertSame(.5, $header->parse());
        $this->assertCount(2, $header->getWarnings());
    }

    public function testGivenAStrictTransportSecurityHeaderWithMissingMaxAgeItShouldWarnAndPenalise()
    {
        $header = new StrictTransportSecurityHeader('includeSubdomains');

        $this->assertSame(.0, $header->parse());
        $this->assertCount(1, $header->getWarnings());
    }
}
