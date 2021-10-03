<?php

namespace App\Domain\DomainEvent;

use App\Infrastructure\SimpleConstructorNormalizer;

class UserCheckedOut implements SimpleConstructorNormalizer
{
    public function __construct(public string $username)
    {
    }
}