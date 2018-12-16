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
    /** @var PostSecurityScanner */
    private $postSecurityScanner;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderValidatorFactory $scannerFactory,
        PostSecurityScanner $postSecurityScanner
    ) {
        $this->headerProvider = $headerProvider;
        $this->scannerFactory = $scannerFactory;
        $this->postSecurityScanner = $postSecurityScanner;
    }

    public function scan(string $url, bool $followRedirects = true): ScanResult
    {
        $headers = $this->getHeaders(new URL($url), $followRedirects);
        $scanResult = new ScanResult();

        foreach ($headers as $header) {
            $scanner = $this->createScanner($header);

            $scanResult->addHeaderResult(
                $scanner->getEvaluatedHeader()
            );
        }

        $this->postScan($scanResult);

        return $scanResult;
    }

    private function getHeaders(URL $url, bool $followRedirects): HttpHeaderBag
    {
        return $this->headerProvider->getHeadersFromUrl($url, $followRedirects);
    }

    private function createScanner(HttpHeader $header): GenericHeaderAuditResult
    {
        return $this->scannerFactory->createFromHeader($header);
    }

    private function postScan(ScanResult $scanResult): void
    {
        $this->postSecurityScanner->postScan($scanResult);
    }
}
