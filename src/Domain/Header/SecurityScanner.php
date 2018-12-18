<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractHeaderAuditResult;
use nicoSWD\SecHeaderCheck\Domain\Result\AuditionResult;
use nicoSWD\SecHeaderCheck\Domain\Result\UnprocessedAuditionResult;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderValidatorFactory;

final class SecurityScanner
{
    /** @var HeaderProviderInterface */
    private $headerProvider;
    /** @var HeaderValidatorFactory */
    private $scannerFactory;
    /** @var ScanResultProcessor */
    private $scanResultProcessor;

    public function __construct(
        HeaderProviderInterface $headerProvider,
        HeaderValidatorFactory $scannerFactory,
        ScanResultProcessor $scanResultProcessor
    ) {
        $this->headerProvider = $headerProvider;
        $this->scannerFactory = $scannerFactory;
        $this->scanResultProcessor = $scanResultProcessor;
    }

    public function scan(string $url, bool $followRedirects = true): AuditionResult
    {
        $headers = $this->getHeaders($url, $followRedirects);
        $auditionResult = new UnprocessedAuditionResult();

        foreach ($headers as $header) {
            $auditionResult->add(
                $this->auditHeader($header)
            );
        }

        return $this->processScanResults($auditionResult);
    }

    private function getHeaders(string $url, bool $followRedirects): HttpHeaderBag
    {
        return $this->headerProvider->getHeadersFromUrl(new URL($url), $followRedirects);
    }

    private function auditHeader(HttpHeader $header): AbstractHeaderAuditResult
    {
        return $this->scannerFactory->createFromHeader($header)->audit();
    }

    private function processScanResults(UnprocessedAuditionResult $scanResult): AuditionResult
    {
        return $this->scanResultProcessor->processScanResults($scanResult);
    }
}
