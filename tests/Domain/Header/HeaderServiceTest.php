<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Header;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use nicoSWD\SecHeaderCheck\Domain\Header\AbstractHeaderProvider;
use nicoSWD\SecHeaderCheck\Domain\Header\HeaderService;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeaders;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderFactory;

final class HeaderServiceTest extends MockeryTestCase
{
    /** @var AbstractHeaderProvider|\Mockery\Mock */
    private $headerProvider;
    /** @var HeaderService */
    private $headerService;

    protected function setUp()
    {
        $this->headerProvider = \Mockery::mock(AbstractHeaderProvider::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->headerService = new HeaderService(
            $this->headerProvider,
            new HeaderFactory(),
            new SecurityHeaders()
        );
    }

    public function testGivenAnUrlWhenValidItShouldReturnTheNumberOfWarnings()
    {
        $this->headerProvider->shouldReceive('getHeaders')->once()->andReturn([]);

        $resultSet = $this->headerService->scan('https://example.com/');

        $warnings = $resultSet->getWarnings();

        $this->assertCount(6, $warnings);
        $this->assertArrayHasKey('x-xss-protection', $warnings);
        $this->assertArrayHasKey('x-frame-options', $warnings);
        $this->assertArrayHasKey('x-content-type-options', $warnings);
        $this->assertArrayHasKey('referrer-policy', $warnings);
        $this->assertArrayHasKey('strict-transport-security', $warnings);
    }
}
