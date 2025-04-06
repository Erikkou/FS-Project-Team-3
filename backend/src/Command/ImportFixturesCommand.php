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

                if (!isset($response['fixtures']) || !is_array($response['fixtures'])) {
                    $output->writeln("Geen geldige 'fixtures' gevonden in API-response.");
                    continue;
                }

                $savedMatches = 0;

                foreach ($response['fixtures'] as $fixture) {
                    if (!isset($fixture['id'], $fixture['name'], $fixture['venue_id'], $fixture['starting_at'])) {
                        $output->writeln("Ongeldige wedstrijddata voor ID " . ($fixture['id'] ?? 'ONBEKEND') . ", overslaan.");
                        continue;
                    }

                    if (!str_contains($fixture['name'], 'vs')) {
                        $output->writeln("Ongeldige wedstrijdnaam: '{$fixture['name']}', overslaan.");
                        continue;
                    }

                    [$home, $away] = explode('vs', $fixture['name']);
                    $teamHome = $this->getTeamByName(trim($home));
                    $teamAway = $this->getTeamByName(trim($away));
                    $stadium = $this->getStadium($fixture['venue_id'] ?? null);

                    if (!$teamHome || !$teamAway || !$stadium) {
                        $output->writeln("Teams of stadion niet gevonden voor fixture {$fixture['id']}, overslaan.");
                        continue;
                    }

                    $homeScore = null;
                    $awayScore = null;

                    if (isset($fixture['scores']) && is_array($fixture['scores'])) {
                        foreach ($fixture['scores'] as $scoreItem) {
                            if (($scoreItem['type_id'] ?? null) === 1525) {
                                if ($scoreItem['score']['participant'] === 'home') {
                                    $homeScore = $scoreItem['score']['goals'] ?? null;
                                } elseif ($scoreItem['score']['participant'] === 'away') {
                                    $awayScore = $scoreItem['score']['goals'] ?? null;
                                }
                            }
                        }
                    }

                    $existingMatch = $this->entityManager->getRepository(Calendar::class)->find($fixture['id']);

                    if ($existingMatch) {
                        $output->writeln("Match {$fixture['id']} bestaat al – bijwerken...");

                        $existingMatch
                            ->setStatus($this->mapStatus($fixture['state_id'] ?? 0))
                            ->setHomeScore($homeScore)
                            ->setAwayScore($awayScore);

                        $this->entityManager->persist($existingMatch);
                        $savedMatches++;
                        continue;
                    }

                    $calendar = new Calendar();
                    $calendar->setId($fixture['id'])
                        ->setHomeTeam($teamHome)
                        ->setAwayTeam($teamAway)
                        ->setStadium($stadium)
                        ->setRound($round)
                        ->setStartingAt(new \DateTime($fixture['starting_at']))
                        ->setStatus($this->mapStatus($fixture['state_id'] ?? 0))
                        ->setHomeScore($homeScore)
                        ->setAwayScore($awayScore);

                    $this->entityManager->persist($calendar);
                    $savedMatches++;
                }

                $this->entityManager->flush();
                $output->writeln("$savedMatches wedstrijden toegevoegd of bijgewerkt.");

            } catch (\Exception $e) {
                $output->writeln("Fout tijdens import: " . $e->getMessage());
            }
        }

        $output->writeln('🏁 Import afgerond.');
        return Command::SUCCESS;
    }

    private function getTeamByName(string $name): ?Team
    {
        return $this->entityManager->getRepository(Team::class)->findOneBy(['name' => $name]);
    }

    private function getStadium(?int $id): ?Stadium
    {
        return $id ? $this->entityManager->getRepository(Stadium::class)->find($id) : null;
    }

    private function mapStatus(int $stateId): string
    {
        return match ($stateId) {
            1 => 'scheduled',
            5 => 'finished',
            12 => 'canceled',
            default => throw new \InvalidArgumentException("Onbekende state_id: $stateId"),
        };
    }
}
