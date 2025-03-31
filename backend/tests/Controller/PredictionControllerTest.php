<?php

namespace Controller;

use App\Controller\PredictionController;
use App\Entity\Calendar;
use App\Entity\Prediction;
use App\Entity\User;
use App\Repository\PredictionRepository;
use App\Service\PredictionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PredictionControllerTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testUpdatePredictions()
    {
        $predictionRepository = $this->createMock(PredictionRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $predictionService = $this->createMock(PredictionService::class);
        $container = $this->createMock(ContainerInterface::class);

        $controller = new PredictionController($predictionService);
        $controller->setContainer($container);

        $prediction = $this->createMock(Prediction::class);
        $user = $this->createMock(User::class);
        $match = $this->createMock(Calendar::class);

        $predictionRepository->method('findAllPendingPredictions')->willReturn([$prediction]);
        $prediction->method('getMatch')->willReturn($match);
        $match->method('getStatus')->willReturn('finished');
        $match->method('getHomeScore')->willReturn(2);
        $match->method('getAwayScore')->willReturn(1);
        $prediction->method('getUser')->willReturn($user);
        $prediction->method('getHomeTeamScore')->willReturn(2);
        $prediction->method('getAwayTeamScore')->willReturn(1);
        $predictionService->method('calculatePoints')->willReturn(9);

        $prediction->expects($this->once())->method('setPoints')->with(9);
        $user->expects($this->once())->method('setScores')->with($this->greaterThanOrEqual(0));

        $entityManager->expects($this->exactly(2))->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $response = $controller->updatePredictions($predictionRepository, $entityManager);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Predictions and user scores updated'], json_decode($response->getContent(), true));
    }
}