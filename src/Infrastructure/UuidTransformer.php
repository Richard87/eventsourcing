<?php

namespace App\Infrastructure;

use EventSauce\UuidEncoding\UuidEncoder;
use Ramsey\Uuid\UuidInterface;

class UuidTransformer implements UuidEncoder
{

    public function encodeUuid(UuidInterface $uuid): string
    {
        return $uuid->toString();
    }

    public function encodeString(string $uuid): string
    {
        return $uuid;
    }
}