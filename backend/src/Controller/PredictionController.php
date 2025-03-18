<?php

namespace App\Controller;

use App\Entity\Prediction;
use App\Entity\User;
use App\Repository\CalendarRepository;
use App\Repository\PredictionRepository;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/api/predictions')]
class PredictionController extends AbstractController
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('', methods: ['POST'])]
    public function createPrediction(Request $request, EntityManagerInterface $em, CalendarRepository $calendarRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        $calendar = $calendarRepository->find($data['calendar_id']);
        if (!$calendar) {
            return $this->json(['error' => 'Calendar not found'], 404);
        }

        $prediction = new Prediction();
        $prediction->setUser($user);
        $prediction->setMatch($calendar);
        $prediction->setHomeTeamScore($data['home_team_score']);
        $prediction->setAwayTeamScore($data['away_team_score']);
        $prediction->setCreatedAt(new \DateTimeImmutable());

        $em->persist($prediction);
        $em->flush();

        return $this->json(['message' => 'Prediction saved'], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getPrediction(PredictionRepository $repository, int $id): JsonResponse
    {
        $prediction = $repository->find($id);
        if (!$prediction) {
            return $this->json(['message' => 'Prediction not found'], 404);
        }
        return $this->json($prediction);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/update-scores', methods: ['POST'])]
    public function updatePredictions(PredictionRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $predictions = $repository->findAllPendingPredictions();

        foreach ($predictions as $prediction) {
            $matchData = $this->apiClient->request("fixtures/{$prediction->getFixture()->getId()}");

            if (!empty($matchData['data'])) {
                $matchResult = $matchData['data'];

                $oldPoints = $prediction->getPoints();
                $prediction->calculatePoints($matchResult['home_score'], $matchResult['away_score']);
                $newPoints = $prediction->getPoints();

                // Update gebruiker score
                $user = $prediction->getUser();
                $user->setScores($user->getScores() - $oldPoints + $newPoints);

                $em->persist($prediction);
                $em->persist($user);
            }
        }

        $em->flush();
        return $this->json(['message' => 'Predictions and user scores updated']);
    }

}
