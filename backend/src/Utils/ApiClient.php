<?php

namespace App\Utils;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
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
        $query['api_token'] = 'D36wq51bFEOQr96obdIxmcnSrfYdw8pp5MirJy9QUYXdlqhVj17UTfhstmQD';

        $response = $this->client->request('GET', 'https://api.sportmonks.com/v3/football/' . $endpoint, [
            'query' => $query,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return $response->toArray();
    }
}
