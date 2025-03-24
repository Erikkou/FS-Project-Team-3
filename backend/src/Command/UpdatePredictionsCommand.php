<?php

namespace App\Command;

use App\Repository\PredictionRepository;
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
        private readonly PredictionRepository   $predictionRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Bezig met bijwerken van voorspellingen...');

        // Haal alle voorspellingen op die nog niet verwerkt zijn
        $predictions = $this->predictionRepository->findAllPendingPredictions();

        foreach ($predictions as $prediction) {
            $match = $prediction->getMatch(); // Ophalen van de gekoppelde wedstrijd (Calendar)

            // Controleer of de wedstrijd is afgelopen
            if ($match->getStatus() !== 'finished') {
                continue;
            }

            // Haal de echte uitslag op
            $homeScore = $match->getHomeScore();
            $awayScore = $match->getAwayScore();

            // Bereken punten
            $oldPoints = $prediction->getPoints();
            $prediction->calculatePoints($homeScore, $awayScore);
            $newPoints = $prediction->getPoints();

            // Update gebruiker score
            $user = $prediction->getUser();
            $user->setScores($user->getScores() - $oldPoints + $newPoints);

            // Opslaan in database
            $this->em->persist($prediction);
            $this->em->persist($user);
        }

        $this->em->flush();

        $output->writeln('Voorspellingen en scores bijgewerkt!');
        return Command::SUCCESS;
    }
}
