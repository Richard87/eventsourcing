<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateCommand;
use App\Infrastructure\SelfExecutingAggregateCommand;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootRepository;

class CheckInUserCommand implements AggregateCommand
{
    public function __construct(public string $uuid, public string $username)
    {
    }
}