<?php

namespace App\Domain\DomainEvent;

use App\Infrastructure\SimpleConstructorNormalizer;

class UserCheckedIn implements SimpleConstructorNormalizer
{
    public function __construct(public string $username)
    {
    }
}