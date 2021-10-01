<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\AggregateRootId;

class Uuid extends \Symfony\Component\Uid\Uuid implements AggregateRootId
{
    public static function create(): self {
        return self::fromString(self::v4()->toRfc4122());
    }

    public static function fromString(string $uuid): Uuid
    {
        return parent::fromString($uuid);
    }

    public function toString(): string
    {
        return $this->toRfc4122();
    }
}