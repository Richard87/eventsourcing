<?php

namespace App\Test;

use App\Domain\Agregate\Building;
use App\Domain\DomainEvent\NewBuildingWasRegistered;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

class TestBuilding extends AggregateRootTestCase
{

    protected function newAggregateRootId(): AggregateRootId
    {
        return Uuid::create();
    }

    protected function aggregateRootClassName(): string
    {
        return Building::class;
    }

    protected function aggregateRootRepository(string $className, MessageRepository $repository, MessageDispatcher $dispatcher, MessageDecorator $decorator): AggregateRootRepository
    {
        return new BuildingRepository($repository);
    }

    protected function setUp(): void
    {
        $this->buildingRepo = new BuildingRepository($this->messageRepository);
    }

    public function handle(Building $building, ...$arguments)
    {
        $this->persistAggregateRoot($building);
        return $building;
    }

    public function testCheckoutUser() {
        $this->when(Building::new("Test building"))->then(new NewBuildingWasRegistered("Test building"));
    }
}