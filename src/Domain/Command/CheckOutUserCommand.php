<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateCommand;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootRepository;

class CheckOutUserCommand implements AggregateCommand
{
    public function __construct(public string $uuid, public string $username)
    {
    }

    public function getClassname(): string
    {
        return Building::class;
    }

    public function __invoke(AggregateRootRepository $repo)
    {
        /** @var Building $building */
        $building = $repo->retrieve(Uuid::fromString($this->uuid));
        $building->checkOutUser($this->username);
        $repo->persist($building);
    }
}