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

final class HeaderService
{
    /** @var HeaderProviderInterface */
    private $headerProvider;
    /** @var HeaderFactory */
    private $headerFactory;

    public function __construct(
        HeaderProviderInterface $headerProvider,
        HeaderFactory $headerFactory
    ) {
        $this->headerProvider = $headerProvider;
        $this->headerFactory = $headerFactory;
    }

    public function analise(string $url): ResultSet
    {
        $foundHeaders = $this->getHeadersFromUrl($url);
        $resultSet = new ResultSet();

        foreach (SecurityHeaders::all() as $headerName) {
            if (!isset($foundHeaders[$headerName])) {
                $resultSet->addWarnings($headerName, ['Header is missing']);
                continue;
            }

            $header = $this->headerFactory->createFromHeader($headerName, $foundHeaders[$headerName]);

            try {
                $resultSet->sumScore($header->getScore());
                $resultSet->addWarnings($headerName, $header->getWarnings());
            } catch (DuplicateHeaderException $e) {
                $resultSet->addWarnings($headerName, ['Header has been sent multiple times']);
            }
        }

        return $resultSet;
    }

    private function getHeadersFromUrl(string $url): array
    {
        if (!$this->isValidUrl($url)) {
            throw new \Exception('Invalid URL');
        }

        $headers = $this->headerProvider->getHeaders($url);
        $headers = array_change_key_case($headers, CASE_LOWER);
        unset($headers[0]);

        return $headers;
    }

    private function isValidUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if ($scheme === false || $scheme === null) {
            return false;
        }

        return in_array($scheme, ['http', 'https'], true);
    }
}
