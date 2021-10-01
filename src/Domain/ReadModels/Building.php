<?php

namespace App\Domain\ReadModels;

use App\Infrastructure\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table("building_buildings")]
class Building
{
    /** @var Collection<int, User> */
    #[OneToMany(mappedBy: "building", targetEntity: User::class, cascade: ["PERSIST"])]
    public Collection $users;

    public function __construct(
        #[Column, Id] public string $uuid,
        #[Column] public string $name
    )
    {
        $this->users = new ArrayCollection();
    }

    public function checkIn(string $name) {
        foreach ($this->users as $user) {
            if ($user->name === $name) {
                $user->checkedIn = true;
                return;
            }
        }

        new User($this, $name, true);
    }

    public function checkOut(string $name) {

        foreach ($this->users as $user) {
            if ($user->name === $name) {
                $user->checkedIn = false;
                return;
            }
        }
    }
}