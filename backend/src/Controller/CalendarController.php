<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Rounds;
use App\Entity\Stadium;
use App\Entity\Team;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CalendarController extends AbstractController
{
    public function __construct(
        private readonly ApiClient $apiClient,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \DateMalformedStringException
     * @throws InvalidArgumentException
     */
    #[Route('/calendar', name: 'set-calendar')]
    public function calender(): JsonResponse
    {
        $response = $this->apiClient->request(
            'rounds/seasons/23628',
            ['include' => 'fixtures.scores', 'league_id' => 72]
        );

        if (!isset($response)) {
            $this->logger->error('No data found in API response');
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No data found in API response',
            ], 404);
        }

        $newMatches = [];
        foreach ($response as $fixtures) {
            foreach ($fixtures['fixtures'] as $fixture) {
                [$home, $away] = explode('vs', $fixture['name']);
                $teamHome = $this->getTeamId(trim($home));
                $teamAway = $this->getTeamId(trim($away));
                $currentScore = array_values(
                    array_filter(
                        $fixture['scores'],
                        static fn($item) => $item['type_id'] === 1525
                    )
                );

                $homeScore = null;
                $awayScore = null;
                foreach ($currentScore as $scoreItem) {
                    if ($scoreItem['score']['participant'] === 'home') {
                        $homeScore = $scoreItem['score']['goals'];
                    } elseif ($scoreItem['score']['participant'] === 'away') {
                        $awayScore = $scoreItem['score']['goals'];
                    }
                }

                // Controleer of de match al bestaat
                $existingMatch = $this->entityManager->getRepository(Calendar::class)->find($fixture['id']);
                if ($existingMatch) {
                    continue;
                }

                $calendar = new Calendar();
                $calendar->setId($fixture['id'])
                    ->setRound($this->getRound($fixture['round_id']))
                    ->setStadium($this->getStadium($fixture['venue_id']))
                    ->setHomeTeam($teamHome)
                    ->setAwayTeam($teamAway)
                    ->setStartingAt((new \DateTime($fixture['starting_at'])))
                    ->setStatus($this->mapStatus($fixture['state_id']))
                    ->setHomeScore($homeScore)
                    ->setAwayScore($awayScore);

                $newMatches[$fixture['round_id']][] = [$fixture['id']];
                $this->entityManager->persist($calendar);
            }
        }
        $this->entityManager->flush();
        return new JsonResponse([
            'status' => 'success',
            'new_matches' => $newMatches,
        ]);
    }

    #[Route('/calendar/round/{roundId}', name: 'show-calendar')]
    public function showCalendar(int $roundId): JsonResponse
    {
        $matches = $this->entityManager->getRepository(Calendar::class)->findBy(['round' => $roundId],
            ['starting_at' => 'ASC']);
        if (count($matches) === 0) {
            return new JsonResponse([
                'status' => 'ERROR',
                'message' => 'Matches not found',
            ]);
        }
        $data = [];
        foreach ($matches as $match) {
            $data[$match->getRound()?->getId()][] = [
                'id' => $match->getId(),
                'home_team' => $this->getTeamName($match->getHomeTeam()?->getId()),
                'away_team' => $this->getTeamName($match->getAwayTeam()?->getId()),
                'date' => $match->getStartingAt()?->format('Y-m-d'),
                'stadium' => $this->getStadiumName($match->getStadium()?->getId()),
            ];
        }
        return new JsonResponse($data);
    }

    protected function getTeamId(string $team)
    {
        return $this->entityManager->getRepository(Team::class)->findOneBy(['name' => $team]);
    }


    protected function getTeamName(int $teamId)
    {
        $teamName = $this->entityManager->getRepository(Team::class)->findOneBy(['id' => $teamId]);
        return $teamName ? $teamName->getName() : 'Geen team gevonden';
    }

    protected function getRound(int $roundId)
    {
        return $this->entityManager->getRepository(Rounds::class)->findOneBy(['id' => $roundId]);
    }

    protected function getStadium(?int $stadiumId)
    {
        return $this->entityManager->getRepository(Stadium::class)->findOneBy(['id' => $stadiumId]);
    }

    protected function getStadiumName(?int $stadiumId)
    {
        $stadium = $this->entityManager->getRepository(Stadium::class)->findOneBy(['id' => $stadiumId]);
        return $stadium ? $stadium->getName() : 'Niet bepaald';
    }

    private function mapStatus(int $stateId): string
    {
        return match ($stateId) {
            1 => 'scheduled',
            5 => 'finished',
            12 => 'canceled',
            default => throw new \InvalidArgumentException("Unknown state_id: $stateId"),
        };
    }

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/calendar/update', name: 'update-calendar')]
    public function updateCalendar(): JsonResponse
    {
        $scheduledMatches = $this->entityManager->getRepository(Calendar::class)->findBy(['status' => 'scheduled']);
        if (empty($scheduledMatches)) {
            return new JsonResponse([
                'status' => 'success',
                'message' => 'No scheduled matches to update.',
            ]);
        }

        $updatedMatches = [];

        foreach ($scheduledMatches as $match) {
            $response = $this->apiClient->request(
                "fixtures/{$match->getId()}",
                ['include' => 'scores']
            );

            if (!$response) {
                continue;
            }

            // Haal de nieuwste status op
            $newStatus = $this->mapStatus($response['state_id'] ?? 1);

            // Haal de scores op als de wedstrijd gespeeld is
            $homeScore = null;
            $awayScore = null;

            if ($newStatus === 'finished') {
                $currentScore = array_values(
                    array_filter(
                        $response['scores'],
                        static fn($item) => $item['type_id'] === 1525
                    )
                );

                foreach ($currentScore as $scoreItem) {
                    if ($scoreItem['score']['participant'] === 'home') {
                        $homeScore = $scoreItem['score']['goals'];
                    } elseif ($scoreItem['score']['participant'] === 'away') {
                        $awayScore = $scoreItem['score']['goals'];
                    }
                }
            }

            // Update alleen als er een verandering is
            if ($newStatus !== $match->getStatus() || $homeScore !== null || $awayScore !== null) {
                $match->setStatus($newStatus);
                $match->setHomeScore($homeScore);
                $match->setAwayScore($awayScore);

                $this->entityManager->persist($match);
                $updatedMatches[] = $match->getId();
            }
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'status' => 'success',
            'updated_matches' => $updatedMatches,
        ]);
    }

}
