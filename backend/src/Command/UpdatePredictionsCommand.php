<?php

namespace App\Command;

use App\Repository\PredictionRepository;
use App\Service\PredictionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-predictions',
    description: 'Update voorspellingen met de actuele wedstrijdresultaten.',
)]
class UpdatePredictionsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PredictionRepository   $predictionRepository,
        private readonly PredictionService      $predictionService
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Bezig met bijwerken van voorspellingen...');

        $predictions = $this->predictionRepository->findAllPendingPredictions();

        foreach ($predictions as $prediction) {
            $match = $prediction->getMatch();
            $prediction->setStatus($match->getStatus());

            if ($match->getStatus() !== 'finished' || $match->getHomeScore() === null || $match->getAwayScore() === null) {
                continue;
            }

            // Haal de echte uitslag op
            $homeScore = $match->getHomeScore();
            $awayScore = $match->getAwayScore();

            // Bereken de punten met de service
            $oldPoints = $prediction->getPoints() ?? 0;
            $newPoints = $this->predictionService->calculatePoints($prediction, $homeScore, $awayScore);
            $prediction->setPoints($newPoints);

            // Update gebruiker score
            $user = $prediction->getUser();
            $user->setScores(max(0, $user->getScores() - $oldPoints + $newPoints));

            $this->em->persist($prediction);
            $this->em->persist($user);
        }

        $this->em->flush();
        $output->writeln('Voorspellingen en scores bijgewerkt!');

        return Command::SUCCESS;
    }

}
