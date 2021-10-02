<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateCommand;
use EventSauce\EventSourcing\AggregateRootRepository;

class RegisterNewBuildingCommand implements AggregateCommand
{
    public function __construct(public string $name)
    {
    }

    public function getClassname(): string
    {
        return Building::class;
    }

    public function __invoke(AggregateRootRepository $repo): \EventSauce\EventSourcing\AggregateRootId
    {
        $building = Building::new($this->name);
        $repo->persist($building);
        return $building->aggregateRootId();
    }
}