<?php

namespace App\Domain\DTO;

use App\Domain\ReadModels\Building;
use App\Domain\ReadModels\User;

class BuildingDetails
{

    /**
     * @param string $uuid
     * @param string $name
     * @param list<string> $users
     */
    public function __construct(
        public string $uuid,
        public string $name,
        public array $users,
    )
    {
    }


    public static function fromBuilding(Building $building): BuildingDetails
    {
        $self = new self(
            $building->uuid,
            $building->name,
            $building->users->map(fn(User $u) =>['name' => $u->name, 'checkedIn' => $u->checkedIn])->toArray()
        );
        return $self;
    }
}