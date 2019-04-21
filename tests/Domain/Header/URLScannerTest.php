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
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ScanResultProcessor;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderParserFactory;

final class URLScannerTest extends MockeryTestCase
{
    /** @var AbstractHeaderProvider|\Mockery\Mock */
    private $headerProvider;
    /** @var URLScanner */
    private $URLScanner;
    /** @var ScanResultProcessor|\Mockery\Mock */
    private $scanResultProcessor;

    protected function setUp()
    {
        $this->headerProvider = \Mockery::mock(AbstractHeaderProvider::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->scanResultProcessor = \Mockery::mock(ScanResultProcessor::class);
        $this->scanResultProcessor->makePartial();

        $this->URLScanner = new URLScanner(
            $this->headerProvider,
            new HeaderParserFactory(),
            $this->scanResultProcessor
        );
    }

    public function testGivenAnUrlWhenValidItShouldReturnTheNumberOfWarnings()
    {
        $this->headerProvider->shouldReceive('getRawHeaders')->once()->andReturn(
            'HTTP/1.1 200 OK' . "\r\n" .
            'Server: nginx' . "\r\n" .
            'Strict-Transport-Security: max-age=31536000; includeSubdomains;'
        );

        $this->scanResultProcessor->shouldReceive('processParsedHeaders')->once()->andReturn(
            \Mockery::mock(AuditionResult::class)->makePartial()
        );

        $resultSet = $this->URLScanner->scanURL('https://example.com/');

        $this->assertInstanceOf(AuditionResult::class, $resultSet);
    }
}
