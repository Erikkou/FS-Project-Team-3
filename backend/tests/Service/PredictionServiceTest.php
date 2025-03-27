<?php

namespace Service;

use App\Entity\Prediction;
use App\Service\PredictionService;
use PHPUnit\Framework\TestCase;

class PredictionServiceTest extends TestCase
{
    private PredictionService $predictionService;

    protected function setUp(): void
    {
        $this->predictionService = new PredictionService();
    }

    public function testCalculatePointsWithNullPrediction()
    {
        $prediction = $this->maakPrediction(0, 0);

        $points = $this->predictionService->calculatePoints($prediction, 2, 1);
        $this->assertEquals(0, $points);
    }

    public function testCalculatePointsExactScore()
    {
        $prediction = $this->maakPrediction(2, 1);
        $points = $this->predictionService->calculatePoints($prediction, 2, 1);
        $this->assertEquals(9, $points);
    }

    public function testCalculatePointsCorrectResultWithExactGoalVerschil()
    {
        $prediction = $this->maakPrediction(3, 1);

        $points = $this->predictionService->calculatePoints($prediction, 2, 0);
        $this->assertEquals(6, $points);
    }

    public function testCalculatePointsCorrectResultWithDifferentGoalDifference()
    {
        $prediction = $this->maakPrediction(2, 1);

        $points = $this->predictionService->calculatePoints($prediction, 3, 1);
        $this->assertEquals(3, $points);
    }

    public function testCalculatePointsWrongResult()
    {
        $prediction = $this->maakPrediction(1, 2);

        $points = $this->predictionService->calculatePoints($prediction, 2, 1);
        $this->assertEquals(0, $points);
    }

    public function testCalculatePointsDrawWithExactScore()
    {
        $prediction = $this->maakPrediction(1, 1);

        $points = $this->predictionService->calculatePoints($prediction, 1, 1);
        $this->assertEquals(9, $points);
    }

    public function testCalculatePointsDrawWithCorrectResult()
    {
        $prediction = $this->maakPrediction(0, 0);

        $points = $this->predictionService->calculatePoints($prediction, 2, 2);
        $this->assertEquals(6, $points);
    }

    public function testCalculatePointsDrawWithWrongResult()
    {
        $prediction = $this->maakPrediction(1, 1);

        $points = $this->predictionService->calculatePoints($prediction, 2, 1);
        $this->assertEquals(0, $points);
    }

    /**
     * @param int $home
     * @param int $away
     * @return Prediction
     */
    public function maakPrediction(int $home, int $away): Prediction
    {
        $prediction = new Prediction();
        $prediction->setHomeTeamScore($home);
        $prediction->setAwayTeamScore($away);
        return $prediction;
    }
}

