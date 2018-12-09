<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderValidatorFactory;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class SecurityScannerService
{
    /** @var AbstractHeaderProvider */
    private $headerProvider;
    /** @var HeaderValidatorFactory */
    private $headerFactory;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderValidatorFactory $headerFactory
    ) {
        $this->headerProvider = $headerProvider;
        $this->headerFactory = $headerFactory;
    }

    public function scan(string $url, bool $followRedirects = true): ScanResult
    {
        $scanResult = new ScanResult();

        foreach ($this->getHeaders($url, $followRedirects) as $header) {
            $scanner = $this->createScanner($header);

            $scanResult->sumScore($header->getName(), $scanner->getCalculatedScore());
            $scanResult->addWarnings($header->getName(), $scanner->getWarnings());
        }

        return $scanResult;
    }

    private function getHeaders(string $url, bool $followRedirects): HeaderBag
    {
        return $this->headerProvider->getHeadersFromUrl($url, $followRedirects);
    }

    private function createScanner(HttpHeader $header): AbstractHeaderValidator
    {
        return $this->headerFactory->createFromHeader($header);
    }
}
