<?php

namespace nicoSWD\SecHeaderCheck\Domain\Headers;

interface HeaderProviderInterface
{
    public function getHeaders(string $url): array;
}