<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MatchesController extends AbstractController
{
    public function __construct(
        private readonly ApiClient $apiClient,
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \DateMalformedStringException
     */
    #[Route('/set-matches', name: 'set_matches')]
    public function setMatches(): JsonResponse
    {
        $response = $this->apiClient->request('fixtures/date/' . $date, ['include' => 'scores']);
        $newMatches = [];

        foreach ($response['data'] as $matchInfo) {
            // Controleer of de match al bestaat
            $existingMatch = $em->getRepository(Matches::class)->find($matchInfo['id']);
            if ($existingMatch) {
                return new JsonResponse([
                    'status' => 'Exists',
                    'Message' => 'The matches have been saved',
                ]);
            }

            // Maak een nieuwe match
            $match = new Matches();
            $match->setId($matchInfo['id'])
                ->setName($matchInfo['name'])
                ->setStageId($matchInfo['league_id'])
                ->setStartingAt((new \DateTime($matchInfo['starting_at']))->format('Y-m-d'))
                ->setEndResultInfo($matchInfo['result_info'] ?? null);

            $em->persist($match);
            $newMatches[] = $matchInfo['id']; // Bewaar de ID van de nieuw toegevoegde match
        }

        // Sla de nieuwe matches op in de database
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'new_matches' => $newMatches,
        ]);
    }

}
