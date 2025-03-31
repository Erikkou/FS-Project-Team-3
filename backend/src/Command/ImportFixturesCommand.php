<?php

namespace App\Command;

use App\Entity\Calendar;
use App\Entity\Rounds;
use App\Entity\Stadium;
use App\Entity\Team;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-fixtures',
    description: 'Importeert wedstrijden per ronde uit SportMonks API',
)]
class ImportFixturesCommand extends Command
{
    public function __construct(
        private readonly ApiClient              $apiClient,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('⚽ Wedstrijden importeren...');
        $rounds = $this->entityManager->getRepository(Rounds::class)->findAll();

        foreach ($rounds as $round) {
            $roundId = $round->getId();
            $output->writeln("🔍 API-aanroep: fixtures voor ronde ID $roundId");

            try {
                $response = $this->apiClient->request(
                    'rounds/seasons/23628',
                    ['include' => 'fixtures.scores', 'league_id' => 72]
                );
                sleep(1);

                if (!array_key_exists('fixtures', $response) || !is_array($response['fixtures'])) {
                    $output->writeln("Geen geldige 'fixtures' gevonden in API-response.");
                    dump($response);
                    continue;
                }

                $savedMatches = 0;

                foreach ($response['fixtures'] as $match) {
                    dump($match);

                    if (!isset($match['id'], $match['name'], $match['venue_id'], $match['starting_at'])) {
                        $output->writeln("Ongeldige wedstrijddata voor ID " . ($match['id'] ?? 'ONBEKEND') . ", overslaan.");
                        continue;
                    }

                    if (!str_contains($match['name'], " vs ")) {
                        $output->writeln("Ongeldige wedstrijdnaam: '{$match['name']}', overslaan.");
                        continue;
                    }

                    [$homeTeamName, $awayTeamName] = explode(" vs ", $match['name']);

                    $homeScore = $awayScore = null;
                    if (!empty($match['result_info'])) {
                        preg_match('/(\d+)-(\d+)/', $match['result_info'], $score);
                        $homeScore = isset($score[1]) ? (int)$score[1] : null;
                        $awayScore = isset($score[2]) ? (int)$score[2] : null;
                    }

                    $existingMatch = $this->entityManager->getRepository(Calendar::class)->find($match['id']);
                    if ($existingMatch) {
                        $output->writeln("Wedstrijd {$match['id']} bestaat al, overslaan.");
                        continue;
                    }

                    $homeTeam = $this->entityManager->getRepository(Team::class)->findOneBy(['name' => $homeTeamName]);
                    $awayTeam = $this->entityManager->getRepository(Team::class)->findOneBy(['name' => $awayTeamName]);
                    $stadium = $this->entityManager->getRepository(Stadium::class)->find($match['venue_id']);

                    if (!$homeTeam || !$awayTeam || !$stadium) {
                        $output->writeln("Team of stadion niet gevonden voor wedstrijd {$match['id']}, overslaan.");
                        continue;
                    }

                    $newFixture = new Calendar();
                    $newFixture->setId($match['id'])
                        ->setHomeTeam($homeTeam)
                        ->setAwayTeam($awayTeam)
                        ->setStadium($stadium)
                        ->setRound($round)
                        ->setStartingAt(new \DateTime($match['starting_at']))
                        ->setStatus($this->mapStatus($match['state_id'] ?? 0))
                        ->setHomeScore($homeScore)
                        ->setAwayScore($awayScore);

                    $this->entityManager->persist($newFixture);
                    $savedMatches++;
                }

                if ($savedMatches > 0) {
                    $this->entityManager->flush();
                    $output->writeln("$savedMatches nieuwe wedstrijden opgeslagen.");
                }
            } catch (\Exception $e) {
                $output->writeln("Fout: " . $e->getMessage());
                dump($e);
            }
        }

        $output->writeln('Import afgerond.');
        return Command::SUCCESS;
    }


    private function mapStatus(int $stateId): string
    {
        return match ($stateId) {
            1 => 'scheduled',
            5 => 'finished',
            12 => 'canceled',
            default => throw new \InvalidArgumentException("Unknown state_id: $stateId"),
        };
    }
}
