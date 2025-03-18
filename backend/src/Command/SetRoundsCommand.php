<?php

namespace App\Command;

use App\Entity\Rounds;
use App\Utils\ApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:set-rounds',
    description: 'Haalt automatisch nieuwe rondes op en slaat ze op in de database.',
)]
class SetRoundsCommand extends Command
{
    public function __construct(
        private readonly ApiClient              $client,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    /**
     * @throws \DateMalformedStringException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Nieuwe rondes ophalen...');

        $response = $this->client->request('rounds', ['filters' => 'roundSeasons:23628']);

        foreach ($response['data'] as $round) {
            $existingRound = $this->entityManager->getRepository(Rounds::class)->find($round['id']);
            if ($existingRound) {
                $output->writeln("Ronde {$round['id']} bestaat al, overslaan.");
                continue;
            }

            $newRound = new Rounds();
            $newRound->setId($round['id'])
                ->setName(sprintf('Round %s', $round['name']))
                ->setStartAt(new \DateTime($round['starting_at']))
                ->setEndAt(new \DateTime($round['ending_at']));

            $this->entityManager->persist($newRound);
            $output->writeln("Ronde {$round['id']} toegevoegd.");
        }

        $this->entityManager->flush();
        $output->writeln('Alle nieuwe rondes zijn opgeslagen.');

        return Command::SUCCESS;
    }
}
