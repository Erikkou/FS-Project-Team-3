<?php

namespace App\Service;

use App\Entity\Prediction;

class PredictionService
{
    public function calculatePoints(Prediction $prediction, int $actualHomeScore, int $actualAwayScore): int
    {
        // Controleer of de voorspelling geldig is
        if ($prediction->getHomeTeamScore() === null || $prediction->getAwayTeamScore() === null) {
            return 0;
        }

        // Exacte score goed
        if ($prediction->getHomeTeamScore() === $actualHomeScore && $prediction->getAwayTeamScore() === $actualAwayScore) {
            return 9;
        }

        // Bepaal de uitkomst van de voorspelling en de echte wedstrijd
        $predictedResult = $prediction->getHomeTeamScore() <=> $prediction->getAwayTeamScore();
        $actualResult = $actualHomeScore <=> $actualAwayScore;

        // Als de winnaar correct is voorspeld
        if ($predictedResult === $actualResult) {
            return (($prediction->getHomeTeamScore() - $prediction->getAwayTeamScore()) ===
                ($actualHomeScore - $actualAwayScore)) ? 6 : 3;
        }

        // Helemaal fout
        return 0;
    }
}
