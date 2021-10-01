<?php

namespace App\Domain\DomainEvent;

class NewBuildingWasRegistered
{
    public function __construct(public string $name)
    {
    }
}