<?php

namespace App\Repository;

use App\Entity\Rounds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rounds>
 */
class RoundsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rounds::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findCurrentRound(): ?Rounds
    {
        return $this->createQueryBuilder('r')
            ->where('r.start_at <= :now')
            ->andWhere('r.end_at >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('r.start_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function getCurrentRound()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
