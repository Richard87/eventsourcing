<?php

namespace App\Domain\Query;

use App\Infrastructure\DomainQuery;

class BuildingDetailsDomainQuery implements DomainQuery
{
    public function __construct(public string $uuid)
    {
    }
}