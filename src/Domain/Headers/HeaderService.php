<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Headers;

use nicoSWD\SecHeaderCheck\Domain\Result\ResultSet;
use nicoSWD\SecHeaderCheck\Domain\Validator\Exception\DuplicateHeaderException;
use nicoSWD\SecHeaderCheck\Domain\Validator\HeaderFactory;
use nicoSWD\SecHeaderCheck\Domain\Validator\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\ValidationError;

final class HeaderService
{
    /** @var AbstractHeaderProvider */
    private $headerProvider;
    /** @var HeaderFactory */
    private $headerFactory;
    /** @var SecurityHeaders */
    private $securityHeaders;

    public function __construct(
        AbstractHeaderProvider $headerProvider,
        HeaderFactory $headerFactory,
        SecurityHeaders $securityHeaders
    ) {
        $this->headerProvider = $headerProvider;
        $this->headerFactory = $headerFactory;
        $this->securityHeaders = $securityHeaders;
    }

    public function analise(string $url): ResultSet
    {
        $foundHeaders = $this->getHeaders($url);
        $resultSet = new ResultSet();

        foreach ($this->securityHeaders->all() as $headerName) {
            if (!$foundHeaders->has($headerName)) {
                $resultSet->addWarnings($headerName, [ValidationError::HEADER_MISSING]);
                continue;
            }

            $headerValidator = $this->createHeaderValidator($headerName, $foundHeaders);

            try {
                $resultSet->sumScore($headerValidator->getScore());
                $resultSet->addWarnings($headerName, $headerValidator->getWarnings());
            } catch (DuplicateHeaderException $e) {
                $resultSet->addWarnings($headerName, [ValidationError::HEADER_DUPLICATE]);
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

    private function createHeaderValidator(string $headerName, HeaderBag $foundHeaders): SecurityHeader
    {
        return $this->headerFactory->createFromHeader($headerName, $foundHeaders->get($headerName));
    }
}
