<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ParsedHeaders;
use nicoSWD\SecHeaderCheck\Domain\Result\ScanResultProcessor;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderParserFactory;

final class HeaderScanner
{
    /** @var HeaderProviderInterface */
    private $headerProvider;
    /** @var HeaderParserFactory */
    private $parserFactory;
    /** @var ScanResultProcessor */
    private $scanResultProcessor;

    public function __construct(
        HeaderProviderInterface $headerProvider,
        HeaderParserFactory $parserFactory,
        ScanResultProcessor $scanResultProcessor
    ) {
        $this->headerProvider = $headerProvider;
        $this->parserFactory = $parserFactory;
        $this->scanResultProcessor = $scanResultProcessor;
    }

    public function scanHeaders(string $headers): AuditionResult
    {
        $headers = $this->getHeaders($headers);
        $parsedHeaders = new ParsedHeaders();

        foreach ($headers as $header) {
            $parsedHeaders->add(
                $this->parseHeader($header)
            );
        }

        return $this->processParsedHeaders($parsedHeaders);
    }

    private function getHeaders(string $headers): HttpHeaderBag
    {
        return $this->headerProvider->getHeadersFromString($headers);
    }

    private function parseHeader(HttpHeader $header): AbstractParsedHeader
    {
        return $this->parserFactory->createFromHeader($header)->parse();
    }

    private function processParsedHeaders(ParsedHeaders $scanResult): AuditionResult
    {
        return $this->scanResultProcessor->processParsedHeaders($scanResult);
    }
}
