<?php

namespace App\Command;

use App\Domain\Agregate\Building;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\MessageRepository\DoctrineV2MessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(private BuildingRepository $buildingRepository, private MessageRepository $messageRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $test = $this->buildingRepository->retrieve("effd0254-fd6f-4fe2-9e39-ca41b3e833f3");
        $test->checkInUser("Richard");
        dump($test->users);
        $this->buildingRepository->persist($test);
        $output->writeln($test->name);
        return Command::SUCCESS;
    }
}
