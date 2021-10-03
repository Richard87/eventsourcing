<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateExecutedCommand;
use App\Infrastructure\HandledByAggregate;
use App\Infrastructure\SelfExecutingAggregateCommand;
use EventSauce\EventSourcing\AggregateRootRepository;

#[HandledByAggregate(Building::class, constructor: "new")]
class RegisterNewBuildingCommand implements AggregateExecutedCommand
{
    public function __construct(public string $name)
    {
    }
}