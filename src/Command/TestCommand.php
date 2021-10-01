<?php

namespace App\Command;

use App\Domain\Agregate\Building;
use App\Domain\Repository\BuildingRepository;
use EventSauce\EventSourcing\EventDispatcher;
use EventSauce\EventSourcing\MessageDispatcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(private BuildingRepository $buildingRepository, private MessageDispatcher $messageDispatcher)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $building = Building::new("Slottet");
        $building->checkInUser("Richard");
        $building->checkOutUser("Richard");
        $this->buildingRepository->persist($building);

        $building = $this->buildingRepository->retrieve($building->aggregateRootId());

        $output->writeln($building->name);
        return Command::SUCCESS;
    }
}
