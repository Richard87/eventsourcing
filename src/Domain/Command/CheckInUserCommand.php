<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateCommand;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootRepository;

class CheckInUserCommand implements AggregateCommand
{
    public function __construct(private string $uuid, private string $username)
    {
    }

    public function getClassname(): string
    {
        return Building::class;
    }

    public function __invoke(AggregateRootRepository $aggregateRootRepository)
    {
        /** @var Building $building */
        $building = $aggregateRootRepository->retrieve(Uuid::fromString($this->uuid));
        $building->checkInUser($this->username);
        $aggregateRootRepository->persist($building);
    }
}