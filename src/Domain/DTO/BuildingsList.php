<?php

namespace App\Domain\DTO;

class BuildingsList
{
    /**
     * @param array<string, string>
     */
    public function __construct(public array $buildings)
    {
    }
}