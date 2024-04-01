<?php

declare(strict_types=1);

namespace Gamez\Mite\Api;

interface ApiClientFactory
{
    /**
     * @param non-empty-string $accountName
     * @param non-empty-string $apiKey
     */
    public function __invoke(string $accountName, string $apiKey): ApiClient;
}
