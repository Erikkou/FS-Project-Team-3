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

class PredictionController extends AbstractController
{
    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    #[Route('/api/predictions', methods: ['POST'])]
    public function createOrUpdatePredictions(
        Request $request,
        EntityManagerInterface $em,
        CalendarRepository $calendarRepository,
        PredictionRepository $predictionRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        if (!is_array($data)) {
            return $this->json(['error' => 'Invalid data format'], 400);
        }

        foreach ($data as $predictionData) {
            $calendar = $calendarRepository->find($predictionData['calendar_id']);
            if (!$calendar) {
                return $this->json(['error' => "Calendar ID {$predictionData['calendar_id']} not found"], 404);
            }

            // Check if the prediction already exists for the user
            $existingPrediction = $predictionRepository->findOneBy([
                'user' => $user,
                'match' => $calendar
            ]);

            if ($existingPrediction) {
                $existingPrediction->setHomeTeamScore($predictionData['home_team_score']);
                $existingPrediction->setAwayTeamScore($predictionData['away_team_score']);
                $em->persist($existingPrediction);
            } else {
                $prediction = new Prediction();
                $prediction->setUser($user);
                $prediction->setMatch($calendar);
                $prediction->setHomeTeamScore($predictionData['home_team_score']);
                $prediction->setAwayTeamScore($predictionData['away_team_score']);
                $prediction->setCreatedAt(new \DateTimeImmutable());

                $em->persist($prediction);
            }
        }

        $em->flush();

        return $this->json(['message' => 'All predictions saved'], 201);
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
