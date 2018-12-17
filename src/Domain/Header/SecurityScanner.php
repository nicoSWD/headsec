<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\GenericHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderValidatorFactory;

final class SecurityScanner
{
    /** @var AbstractHeaderProvider */
    private $headerProvider;
    /** @var HeaderValidatorFactory */
    private $scannerFactory;
    /** @var ScanResultProcessor */
    private $scanResultProcessor;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderValidatorFactory $scannerFactory,
        ScanResultProcessor $scanResultProcessor
    ) {
        $this->headerProvider = $headerProvider;
        $this->scannerFactory = $scannerFactory;
        $this->scanResultProcessor = $scanResultProcessor;
    }

    public function scan(string $url, bool $followRedirects = true): ScanResult
    {
        $headers = $this->getHeaders($url, $followRedirects);
        $scanResult = new ScanResult();

        foreach ($headers as $header) {
            $scanResult->addHeaderResult(
                $this->auditHeader($header)
            );
        }

        $this->processScanResults($scanResult);

        return $scanResult;
    }

    private function getHeaders(string $url, bool $followRedirects): HttpHeaderBag
    {
        return $this->headerProvider->getHeadersFromUrl(new URL($url), $followRedirects);
    }

    private function auditHeader(HttpHeader $header): GenericHeaderAuditResult
    {
        return $this->scannerFactory->createFromHeader($header);
    }

    private function processScanResults(ScanResult $scanResult): void
    {
        $this->scanResultProcessor->processScanResults($scanResult);
    }
}
