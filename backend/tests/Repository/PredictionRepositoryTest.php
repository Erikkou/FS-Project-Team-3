<?php

namespace Repository;

use App\Entity\Calendar;
use App\Entity\Prediction;
use App\Repository\PredictionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class PredictionRepositoryTest extends TestCase
{
    public function testFindAllPendingPredictions()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(\Doctrine\ORM\AbstractQuery::class);
        $classMetadata = $this->createMock(ClassMetadata::class);

        $managerRegistry->method('getManagerForClass')->willReturn($entityManager);
        $managerRegistry->method('getManager')->willReturn($entityManager);

        $entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
        $entityManager->method('getClassMetadata')->willReturn($classMetadata);

        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('innerJoin')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('getQuery')->willReturn($query);

        $match = $this->createMock(Calendar::class);
        $match->method('getStatus')->willReturn('finished');

        $expectedPrediction = new Prediction();
        $expectedPrediction->setMatch($match);
        $expectedPrediction->setPoints(0);
        $expectedPrediction->setStatus('finished');
        $expectedResult = [$expectedPrediction];

        $query->method('getResult')->willReturn($expectedResult);

        $repository = new PredictionRepository($managerRegistry);
        $result = $repository->findAllPendingPredictions();

        $this->assertSame($expectedResult, $result);
    }

    public function testFindAllPendingPredictionsDoesNotMatch()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(\Doctrine\ORM\AbstractQuery::class);
        $classMetadata = $this->createMock(ClassMetadata::class);

        $managerRegistry->method('getManagerForClass')->willReturn($entityManager);
        $managerRegistry->method('getManager')->willReturn($entityManager);

        $entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
        $entityManager->method('getClassMetadata')->willReturn($classMetadata);

        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('innerJoin')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('andWhere')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('getQuery')->willReturn($query);

        $match = $this->createMock(Calendar::class);
        $match->method('getStatus')->willReturn('pending');

        $query->method('getResult')->willReturn([]);

        $repository = new PredictionRepository($managerRegistry);
        $result = $repository->findAllPendingPredictions();

        $this->assertEmpty($result);
    }
}