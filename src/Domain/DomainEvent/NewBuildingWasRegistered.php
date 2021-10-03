<?php

namespace App\Domain\DomainEvent;

use App\Infrastructure\SimpleConstructorNormalizer;

class NewBuildingWasRegistered implements SimpleConstructorNormalizer
{
    public function __construct(public string $name)
    {
    }
}