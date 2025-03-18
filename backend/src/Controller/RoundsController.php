<?php

namespace App\Controller;

use App\Entity\Rounds;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RoundsController extends AbstractController
{

    public function __construct(
        private readonly ApiClient              $client,
        private readonly EntityManagerInterface $entityManager,

    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \DateMalformedStringException
     */
    #[Route('/api/set/rounds', name: 'set_rounds')]
    public function setRounds(): JsonResponse
    {
        $response = $this->client->request('rounds', ['filters' => 'roundSeasons:23628']);
        foreach ($response['data'] as $round) {
            // Check if the round have been already saved in DB
            $existingRound = $this->entityManager->getRepository(Rounds::class)->find($round['id']);
            if ($existingRound) {
                return new JsonResponse([
                    'status' => 'Exists',
                    'Message' => 'The round have been saved',
                ]);
            }

            $rounds = new Rounds();
            $rounds->setId($round['id'])
                ->setName(sprintf('Round %s', $round['name']))
                ->setStartAt(new \DateTime($round['starting_at']))
                ->setEndAt(new \DateTime($round['ending_at']));
            $this->entityManager->persist($rounds);
        }
        $this->entityManager->flush();
        return new JsonResponse(['status' => 'success', 'message' => 'Rounds have been saved']);
    }

    #[Route('/api/show/rounds/week', name: 'show_rounds_week', methods: ['GET'])]
    public function showRoundsPerWeek(): JsonResponse
    {
        $weekStart = new \DateTimeImmutable('monday this week 00:00:00');
        $weekEnd = new \DateTimeImmutable('sunday this week 23:59:59');

        $rounds = $this->entityManager->getRepository(Rounds::class)->createQueryBuilder('r')
            ->where('r.start_at BETWEEN :weekStart AND :weekEnd')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->getQuery()
            ->getResult();

        if (empty($rounds)) {
            $rounds = $this->entityManager->getRepository(Rounds::class)->createQueryBuilder('r')
                ->orderBy('r.start_at', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
        }

        $data = [];
        foreach ($rounds as $round) {
            $data[] = [
                'id' => $round->getId(),
                'name' => $round->getName(),
                'starting_at' => $round->getStartAt()->format('Y-m-d'),
                'ending_at' => $round->getEndAt()->format('Y-m-d'),
            ];
        }

        return new JsonResponse($data);
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/api/show/rounds/{currentRound}', name: 'show_previous_next_round', methods: ['GET'])]
    public function showPreviousNextRound(int $currentRound): JsonResponse
    {
        // Get the highest round number (last round)
        $maxRound = $this->entityManager->getRepository(Rounds::class)
            ->createQueryBuilder('r')
            ->select('MAX(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Get previous and next rounds
        $previousRound = ($currentRound > 1) ?
            $this->entityManager->getRepository(Rounds::class)->findOneBy(['id' => $currentRound - 1])
            : null;

        $nextRound = ($currentRound < $maxRound) ?
            $this->entityManager->getRepository(Rounds::class)->findOneBy(['id' => $currentRound + 1])
            : null;

        // Get current round
        $currentRoundData = $this->entityManager->getRepository(Rounds::class)->findOneBy(
            ['id' => $currentRound]
        );

        // Build response
        $data = [
            'current_round' => $currentRoundData ? [
                'id' => $currentRoundData->getId(),
                'name' => $currentRoundData->getName(),
                'starting_at' => $currentRoundData->getStartAt()->format('Y-m-d'),
                'ending_at' => $currentRoundData->getEndAt()->format('Y-m-d'),
            ] : null,
            'previous_round' => $previousRound ? [
                'id' => $previousRound->getId(),
                'name' => $previousRound->getName(),
                'starting_at' => $previousRound->getStartAt()->format('Y-m-d'),
                'ending_at' => $previousRound->getEndAt()->format('Y-m-d'),
            ] : null,
            'next_round' => $nextRound ? [
                'id' => $nextRound->getId(),
                'name' => $nextRound->getName(),
                'starting_at' => $nextRound->getStartAt()->format('Y-m-d'),
                'ending_at' => $nextRound->getEndAt()->format('Y-m-d'),
            ] : null,
        ];

        return new JsonResponse($data);
    }
}
