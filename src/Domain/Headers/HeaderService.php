<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Domain\Headers;

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

    public function analise(string $url)
    {
        $score = 0;
        $recommendations = [];

        foreach ($this->getHeaders($url) as $header => $value) {
            $head = $this->headerFactory->createFromHeader($header, $value);

            $score += $head->getScore();
            $recommendations += $head->getRecommendations();
        }

        return $score;
    }

    private function getHeaders(string $url)
    {
        $headers = $this->headerProvider->getHeaders($url);
        $headers =  array_change_key_case($headers, CASE_LOWER);
        unset($headers[0]);

        return $headers;
    }
}
