<?php

namespace App\Domain\Command;

use App\Domain\Agregate\Building;
use App\Infrastructure\AggregateExecutedCommand;
use App\Infrastructure\HandledByAggregate;

#[HandledByAggregate(Building::class, method: "checkOutUser")]
class CheckOutUserCommand implements AggregateExecutedCommand
{
    public function __construct(public string $uuid, public string $username)
    {
    }
}