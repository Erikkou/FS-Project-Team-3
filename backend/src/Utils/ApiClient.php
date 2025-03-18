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

        try {
            $response = $this->client->request('GET', 'https://api.sportmonks.com/v3/football/' . $endpoint, [
                'query' => $query,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = $response->toArray();

            // Controleer of 'data' aanwezig is en een array is
            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception('Geen geldige data ontvangen van de API: ' . json_encode($data));
            }

            return $data['data'];
        } catch (TransportExceptionInterface|ServerExceptionInterface|
        RedirectionExceptionInterface|DecodingExceptionInterface|
        ClientExceptionInterface $e) {
            throw new \Exception('API-aanvraag mislukt: ' . $e->getMessage());
        }
    }

}
