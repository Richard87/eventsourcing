<?php

namespace App\Domain\DomainEvent;

class UserCheckedOut
{
    public function __construct(public string $username)
    {
    }
}