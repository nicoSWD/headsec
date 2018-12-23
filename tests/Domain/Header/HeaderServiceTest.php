<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Header;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use nicoSWD\SecHeaderCheck\Domain\Header\AbstractHeaderProvider;
use nicoSWD\SecHeaderCheck\Domain\Header\URLScanner;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderParserFactory;

final class HeaderServiceTest extends MockeryTestCase
{
    /** @var AbstractHeaderProvider|\Mockery\Mock */
    private $headerProvider;
    /** @var URLScanner */
    private $headerService;

    protected function setUp()
    {
        $this->headerProvider = \Mockery::mock(AbstractHeaderProvider::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->headerService = new URLScanner(
            $this->headerProvider,
            new HeaderParserFactory(),
            new SecurityHeader()
        );
    }

    public function testGivenAnUrlWhenValidItShouldReturnTheNumberOfWarnings()
    {
        $this->headerProvider->shouldReceive('getHeaders')->once()->andReturn([]);

        $resultSet = $this->headerService->scanURL('https://example.com/');

        $warnings = $resultSet->getWarnings();

        $this->assertCount(6, $warnings);
        $this->assertArrayHasKey('x-xss-protection', $warnings);
        $this->assertArrayHasKey('x-frame-options', $warnings);
        $this->assertArrayHasKey('x-content-type-options', $warnings);
        $this->assertArrayHasKey('referrer-policy', $warnings);
        $this->assertArrayHasKey('strict-transport-security', $warnings);
    }
}
