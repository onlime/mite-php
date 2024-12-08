<?php

declare(strict_types=1);

namespace Gamez\Mite\Api;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use SensitiveParameter;

final class HttpApiClientFactory implements ApiClientFactory
{
    private RequestFactoryInterface $requestFactory;

    private ClientInterface $httpClient;

    public function __construct(
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) {
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
    }

    public function __invoke(string $accountName, #[SensitiveParameter] string $apiKey): ApiClient
    {
        return HttpApiClient::with($accountName, $apiKey, $this->httpClient, $this->requestFactory);
    }
}
