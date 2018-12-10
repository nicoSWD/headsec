<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\URL\URL;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderValidatorFactory;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class SecurityScannerService
{
    /** @var AbstractHeaderProvider */
    private $headerProvider;
    /** @var HeaderValidatorFactory */
    private $scannerFactory;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderValidatorFactory $scannerFactory
    ) {
        $this->headerProvider = $headerProvider;
        $this->scannerFactory = $scannerFactory;
    }

    public function scan(string $url, bool $followRedirects = true): ScanResult
    {
        $scanResult = new ScanResult();

        foreach ($this->getHeaders(new URL($url), $followRedirects) as $header) {
            $scanner = $this->createScanner($header);

            $scanResult->sumScore($header->name(), $scanner->getCalculatedScore());
            $scanResult->addWarnings($header->name(), $scanner->getWarnings());
        }

        return $scanResult;
    }

    private function getHeaders(URL $url, bool $followRedirects): HeaderBag
    {
        return $this->headerProvider->getHeadersFromUrl($url, $followRedirects);
    }

    private function createScanner(HttpHeader $header): AbstractHeaderValidator
    {
        return $this->scannerFactory->createFromHeader($header);
    }
}