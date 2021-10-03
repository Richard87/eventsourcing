<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateCommand;
use App\Infrastructure\AggregateExecutedCommand;
use App\Infrastructure\HandledByAggregate;
use App\Infrastructure\SelfExecutingAggregateCommand;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootRepository;

#[HandledByAggregate(Building::class, method: "checkInUser")]
class CheckInUserCommand implements AggregateExecutedCommand
{
    public function __construct(public string $uuid, public string $username)
    {
    }
}