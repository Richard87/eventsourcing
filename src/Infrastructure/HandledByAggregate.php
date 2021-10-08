<?php

namespace App\Infrastructure;

#[\Attribute(\Attribute::TARGET_CLASS)]
class HandledByAggregate
{
    public function __construct(public string $className, public ?string $method = null, public ?string $constructor = null, public string $identifier = "uuid")
    {
    }
}