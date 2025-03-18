<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Stadium;
use App\Entity\Team;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly EntityManagerInterface $entityManager
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
    #[Route('/calendar', name: 'set-calendar')]
    public function calender(): JsonResponse
    {
        $response = $this->apiClient->request('rounds/seasons/23628', ['include' => 'fixtures', 'league_id' => 72]);

        $newMatches = [];
        foreach ($response['data'] as $fixtures) {
            foreach ($fixtures['fixtures'] as $fixture) {
                // Controleer of de match al bestaat
                $existingMatch = $this->entityManager->getRepository(Calendar::class)->find($fixture['id']);
                if ($existingMatch) {
                    return new JsonResponse([
                        'status' => 'Exists',
                        'Message' => 'The matches have been saved',
                    ]);
                }

                [$home, $away] = explode('vs', $fixture['name']);
                $calendar = new Calendar();
                $calendar->setId($fixture['id'])
                    ->setRound($fixture['round_id'])
                    ->setStadium($fixture['venue_id'])
                    ->setHomeTeam($this->getTeamId(trim($home)))
                    ->setAwayTeam($this->getTeamId(trim($away)))
                    ->setStartingAt((new \DateTime($fixture['starting_at'])));

                $newMatches[$fixture['round_id']][] = $fixture['id'];
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
        $matches = $this->entityManager->getRepository(Calendar::class)->findBy(['round_id' => $roundId]);
        if (count($matches) === 0) {
            return new JsonResponse([
                'status' => 'ERROR',
                'message' => 'Matches not found',
            ]);
        }
        $data = [];
        foreach ($matches as $match) {
            $data[$match->getRoundId()][] = [
                'id' => $match->getId(),
                'home_team' => $this->getTeamName($match->getHomeTeam()),
                'away_team' => $this->getTeamName($match->getAwayTeam()),
                'date' => $match->getStartingAt()->format('Y-m-d'),
                'stadium' => $this->getStadiumName($match->getStadiumId()),
            ];
        }
        return new JsonResponse($data);
    }

    protected function getTeamId(string $team): ?int
    {
        $teamEntity = $this->entityManager->getRepository(Team::class)->findOneBy(['name' => $team]);
        return $teamEntity ? $teamEntity->getId() : null;
    }


    protected function getTeamName(int $teamId)
    {
        $teamName = $this->entityManager->getRepository(Team::class)->findOneBy(['id' => $teamId]);
        return $teamName ? $teamName->getName() : 'Geen team gevonden';
    }

    protected function getStadiumName(int $stadiumId)
    {
        $stadium = $this->entityManager->getRepository(Stadium::class)->findOneBy(['id' => $stadiumId]);
        return $stadium ? $stadium->getName() : 'Niet bepaald';
    }

}
