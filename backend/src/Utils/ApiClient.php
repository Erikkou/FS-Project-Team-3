<?php

namespace App\Utils;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $client;
    private string $apiToken;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $parameterBag)
    {
        $this->client = $client;
        $this->apiToken = $parameterBag->get('sportmonks_api_token');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function request(string $endpoint, array $query = []): array
    {
        $query['api_token'] = $this->apiToken;

        $response = $this->client->request('GET', 'https://api.sportmonks.com/v3/football/' . $endpoint, [
            'query' => $query,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return $response->toArray();
    }
}
