<?php

namespace App\Controller;

use App\Entity\Prediction;
use App\Entity\User;
use App\Repository\CalendarRepository;
use App\Repository\PredictionRepository;
use App\Service\PredictionService;
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
    private PredictionService $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    #[Route('/api/predictions', methods: ['POST'])]
    public function createOrUpdatePredictions(
        Request                $request,
        EntityManagerInterface $em,
        CalendarRepository     $calendarRepository,
        PredictionRepository   $predictionRepository
    ): JsonResponse
    {
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
            $status = $calendar->getStatus();

            // Check if the prediction already exists for the user
            $existingPrediction = $predictionRepository->findOneBy([
                'user' => $user,
                'match' => $calendar
            ]);

            if ($existingPrediction) {
                $existingPrediction->setHomeTeamScore($predictionData['home_team_score']);
                $existingPrediction->setAwayTeamScore($predictionData['away_team_score']);
                $existingPrediction->setStatus($status);
                $em->persist($existingPrediction);
            } else {
                $prediction = new Prediction();
                $prediction->setUser($user);
                $prediction->setMatch($calendar);
                $prediction->setHomeTeamScore($predictionData['home_team_score']);
                $prediction->setAwayTeamScore($predictionData['away_team_score']);
                $prediction->setCreatedAt(new \DateTimeImmutable());
                $prediction->setStatus($status);

                $em->persist($prediction);
            }
        }

        $em->flush();

        return $this->json(['message' => 'All predictions saved', 'match_status' => $status], 201);
    }

    #[Route('/api/user/predictions', methods: ['GET'])]
    public function getUserPredictions(PredictionRepository $repository): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        $predictions = $repository->findBy(['user' => $user]);

        $data = array_map(function (Prediction $prediction) {
            return [
                'match' => [
                    'id' => $prediction->getMatch()->getId(),
                    'home_team' => $prediction->getMatch()->getHomeTeam()->getName(),
                    'away_team' => $prediction->getMatch()->getAwayTeam()->getName(),
                    'home_score' => $prediction->getMatch()->getHomeScore(),
                    'away_score' =>$prediction->getMatch()->getAwayScore(),
                    'status' => $prediction->getMatch()->getStatus(),
                ],
                'home_team_score' => $prediction->getHomeTeamScore(),
                'away_team_score' => $prediction->getAwayTeamScore(),
                'points' => $prediction->getPoints(),
            ];
        }, $predictions);

        return $this->json($data);
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
            $match = $prediction->getMatch();
            $prediction->setStatus($match->getStatus());
            // Zorg dat de wedstrijd is afgerond en een score heeft
            if ($match->getStatus() !== 'finished' || $match->getHomeScore() === null || $match->getAwayScore() === null) {
                continue;
            }

            $homeScore = $match->getHomeScore();
            $awayScore = $match->getAwayScore();

            // Bereken nieuwe punten
            $oldPoints = $prediction->getPoints() ?? 0;
            $newPoints = $this->predictionService->calculatePoints($prediction, $homeScore, $awayScore);
            $prediction->setPoints($newPoints);

            // Update gebruiker score
            $user = $prediction->getUser();
            $user->setScores(max(0, $user->getScores() - $oldPoints + $newPoints));

            $em->persist($prediction);
            $em->persist($user);
        }

        $em->flush();

        return $this->json(['message' => 'Predictions and user scores updated']);
    }

}
