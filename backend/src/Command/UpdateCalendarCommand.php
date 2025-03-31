<?php

namespace App\Command;

use App\Controller\CalendarController;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UpdateCalendarCommand extends Command
{
    protected static $defaultName = 'app:fill-calendar';

    private CalendarController $calendarController;

    public function __construct(CalendarController $calendarController)
    {
        $this->calendarController = $calendarController;
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \DateMalformedStringException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->calendarController->calender();
        $output->writeln($response->getContent());
        return Command::SUCCESS;
    }
}