<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\ScanResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\Exception\DuplicateHeaderException;
use nicoSWD\SecHeaderCheck\Domain\Validator\Exception\MissingMandatoryHeaderException;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderValidatorFactory;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;

final class HeaderService
{
    /** @var AbstractHeaderProvider */
    private $headerProvider;
    /** @var HeaderValidatorFactory */
    private $headerFactory;
    /** @var SecurityHeaders */
    private $securityHeaders;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderValidatorFactory $headerFactory,
        SecurityHeaders $securityHeaders
    ) {
        $this->headerProvider = $headerProvider;
        $this->headerFactory = $headerFactory;
        $this->securityHeaders = $securityHeaders;
    }

    public function scan(string $url): ScanResult
    {
        $foundHeaders = $this->getHeaders($url);
        $resultSet = new ScanResult();

        foreach ($this->securityHeaders->getAll() as $header) {
            try {
                $validator = $this->createHeaderValidator($header, $foundHeaders);

                $resultSet->sumScore($validator->getCalculatedScore());
                $resultSet->addWarnings($header->getName(), $validator->getWarnings());
            } catch (DuplicateHeaderException $e) {
                $resultSet->addDuplicateHeaderWarning($header->getName());
            } catch (MissingMandatoryHeaderException $e) {
                $resultSet->addMissingHeaderWarning($header->getName());
            }
        }

        return $resultSet;
    }

    private function getHeaders(string $url): HeaderBag
    {
        return new HeaderBag(
            $this->headerProvider->getHeadersFromUrl($url)
        );
    }

    private function createHeaderValidator(Header $header, HeaderBag $foundHeaders): AbstractHeaderValidator
    {
        $headerName = $header->getName();

        if (!$foundHeaders->has($headerName) && $header->isMandatory()) {
            throw new MissingMandatoryHeaderException();
        }

        return $this->headerFactory->createFromHeader($headerName, $foundHeaders->get($headerName));
    }
}
