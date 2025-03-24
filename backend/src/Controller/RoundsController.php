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
//    #[Route('/api/set/rounds', name: 'set_rounds')]
//    public function setRounds(): JsonResponse
//    {
//        try {
//            $response = $this->client->request('rounds', ['filters' => 'roundSeasons:23628']);
//
//            //  Check of er 'data' in de response zit
//            if (!isset($response['data']) || !is_array($response['data'])) {
//                return new JsonResponse(['status' => 'error', 'message' => 'Ongeldige API-response of geen data ontvangen'], 400);
//            }
//
//
//            $newRounds = 0;
//            foreach ($response['data'] as $round) {
//                $existingRound = $this->entityManager->getRepository(Rounds::class)->find($round['id']);
//
//                if (!$existingRound) {
//                    $newRound = new Rounds();
//                    $newRound->setId($round['id'])
//                        ->setName(sprintf('Round %s', $round['name']))
//                        ->setStartAt(new \DateTime($round['starting_at']))
//                        ->setEndAt(new \DateTime($round['ending_at']));
//
//                    $this->entityManager->persist($newRound);
//                    $newRounds++;
//                }
//            }
//
//            // Data opslaan in database
//            $this->entityManager->flush();
//
//            return new JsonResponse([
//                'status' => 'success',
//                'message' => "$newRounds nieuwe rondes opgeslagen"
//            ]);
//        } catch (\Exception $e) {
//            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
//        }
//    }

    #[Route('/api/show/rounds/week', name: 'show_rounds_week', methods: ['GET'])]
    public function showRoundsPerWeek(): JsonResponse
    {
        $weekStart = new \DateTimeImmutable('monday this week 00:00:00');
        $weekEnd = new \DateTimeImmutable('sunday this week 23:59:59');

        $rounds = $this->entityManager->getRepository(Rounds::class)->createQueryBuilder('r')
            ->where('r.start_at BETWEEN :weekStart AND :weekEnd')
            ->setParameter('weekStart', $weekStart)
            ->setParameter('weekEnd', $weekEnd)
            ->orderBy('r.start_at', 'ASC')
            ->getQuery()
            ->getResult();
        // Als er geen rondes zijn deze week, pak de eerstvolgende
        if (empty($rounds)) {
            $rounds = $this->entityManager->getRepository(Rounds::class)->createQueryBuilder('r')
                ->orderBy('r.start_at', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
        }

        // Controle of er nog steeds geen rondes zijn
        if (empty($rounds)) {
            return new JsonResponse(['message' => 'Geen rondes gevonden voor deze week of daarna'], 404);
        }

        $data = [];
        foreach ($rounds as $round) {
            $data = [
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
        // Check of ronde bestaat
        $currentRoundData = $this->entityManager->getRepository(Rounds::class)->find($currentRound);
        if (!$currentRoundData) {
            return new JsonResponse(['error' => 'Ronde niet gevonden'], 404);
        }

        // Bepaal de max ronde (laatste ronde)
        $maxRound = $this->entityManager->getRepository(Rounds::class)
            ->createQueryBuilder('r')
            ->select('MAX(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Ophalen vorige en volgende ronde
        $previousRound = ($currentRound > 1) ?
            $this->entityManager->getRepository(Rounds::class)->find($currentRound - 1) : null;

        $nextRound = ($currentRound < $maxRound) ?
            $this->entityManager->getRepository(Rounds::class)->find($currentRound + 1) : null;

        return new JsonResponse([
            'current_round' => [
                'id' => $currentRoundData->getId(),
                'name' => $currentRoundData->getName(),
                'starting_at' => $currentRoundData->getStartAt()->format('Y-m-d'),
                'ending_at' => $currentRoundData->getEndAt()->format('Y-m-d'),
            ],
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
        ]);
    }
}
