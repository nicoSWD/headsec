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

final class SecurityScanner
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
        $headers = $this->getHeaders(new URL($url), $followRedirects);
        $scanResult = new ScanResult();

        foreach ($headers as $header) {
            $scanResult->addHeader(
                $this->createScanner($header)->getEvaluatedHeader()
            );
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
