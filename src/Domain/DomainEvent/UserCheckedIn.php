<?php

namespace App\Domain\DomainEvent;

class UserCheckedIn
{
    public function __construct(public string $username)
    {
    }
}