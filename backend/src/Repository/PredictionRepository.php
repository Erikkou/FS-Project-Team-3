<?php

namespace App\Repository;

use App\Entity\Prediction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prediction>
 */
class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    /**
     * Haal alle voorspellingen op van een specifieke gebruiker.
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Haal alle voorspellingen op voor een specifieke wedstrijd.
     */
    public function findByMatch(int $matchId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.match = :match')
            ->setParameter('match', $matchId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Controleer of een gebruiker al een voorspelling heeft gedaan voor een wedstrijd.
     * @throws NonUniqueResultException
     */
    public function findUserPredictionForMatch(int $userId, int $matchId): ?Prediction
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.match = :match')
            ->setParameter('user', $userId)
            ->setParameter('match', $matchId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllPendingPredictions()
    {
        return $this->createQueryBuilder('p')
            ->join('p.match', 'm')
            ->where('m.status = :finished')
            ->andWhere('p.points IS NULL') // Zorgt ervoor dat alleen niet-verwerkte voorspellingen worden opgehaald
            ->setParameter('finished', 'finished')
            ->getQuery()
            ->getResult();
    }


}
