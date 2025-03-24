<?php

namespace App\Utils;

use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private HttpClientInterface $client;
    private string $apiBaseUrl;
    private string $apiToken;
    private LoggerInterface $logger;
    private CacheInterface $cache;

    public function __construct(
        HttpClientInterface   $client,
        ParameterBagInterface $parameterBag,
        LoggerInterface       $logger,
        CacheInterface        $cache
    )
    {
        $this->client = $client;
        $this->apiBaseUrl = $parameterBag->get('sportmonks_api_base_url');
        $this->apiToken = $parameterBag->get('sportmonks_api_token');
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * Voert een API-verzoek uit en retourneert de gegevens.
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     */
    public function request(string $endpoint, array $query = []): array
    {
        // Cache-key genereren
        $cacheKey = 'sportmonks_' . md5($endpoint . json_encode($query));

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($endpoint, $query) {
            $item->expiresAfter(600); // Cache voor 10 minuten
            return $this->fetchDataFromApi($endpoint, $query);
        });
    }

    /**
     * Voert de daadwerkelijke API-aanroep uit en verwerkt het resultaat.
     */
    private function fetchDataFromApi(string $endpoint, array $query): array
    {
        $query['api_token'] = $this->apiToken;

        try {
            $response = $this->client->request('GET', $this->apiBaseUrl . $endpoint, [
                'query' => $query,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            // Controleer op rate limiting (status 429)
            if ($response->getStatusCode() === 429) {
                $this->logger->warning("Rate limit bereikt voor endpoint: $endpoint. Wachten en opnieuw proberen...");
                sleep(60); // Wacht 60 seconden
                return $this->fetchDataFromApi($endpoint, $query); // Herhaal verzoek
            }

            $data = $response->toArray();

            if (!isset($data['data']) || !is_array($data['data'])) {
                throw new \Exception('Ongeldige data ontvangen: ' . json_encode($data));
            }

            return $data['data'];
        } catch (TransportExceptionInterface|
        ServerExceptionInterface|
        RedirectionExceptionInterface|
        DecodingExceptionInterface|
        ClientExceptionInterface $e) {
            $this->logger->error('API-aanvraag mislukt', [
                'endpoint' => $endpoint,
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            throw new \Exception('API-aanvraag mislukt: ' . $e->getMessage());
        }
    }
}
