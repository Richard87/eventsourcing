<?php

namespace App\Command;

use App\Domain\Agregate\Building;
use App\Domain\Command\CheckInUserCommand;
use App\Domain\Command\CheckOutUserCommand;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\EventDispatcher;
use EventSauce\EventSourcing\MessageDispatcher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
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
        $building = $this->buildingRepository->retrieve(Uuid::fromString("46c02081-8810-4fb2-9b50-57deb44bff56"));

        foreach (range(1,5000) as $i) {
            $building->checkOutUser(new CheckOutUserCommand("46c02081-8810-4fb2-9b50-57deb44bff56", "Richard"));
            $building->checkInUser(new CheckInUserCommand("46c02081-8810-4fb2-9b50-57deb44bff56", "Richard"));
        }
        $this->buildingRepository->persist($building);

        $output->writeln($building->name);
        return Command::SUCCESS;
    }
}
