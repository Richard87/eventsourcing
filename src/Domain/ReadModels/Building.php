<?php

namespace App\Domain\ReadModels;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Command\CheckInUserCommand;
use App\Domain\Command\CheckOutUserCommand;
use App\Domain\Command\RegisterNewBuildingCommand;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use EventSauce\EventSourcing\AggregateRootId;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get" => ["normalization_context" => ["groups" => ["list"]]],
        "register" => ["status" => 200, "messenger" => "input", "input" => RegisterNewBuildingCommand::class, "output" => AggregateRootId::class, "method" => "POST", "path" => "/buildings/register_new_building"],
        "check_in" => ["status" => 202, "messenger" => "input", "input" => CheckInUserCommand::class, "output" => false, "method" => "POST", "path" => "/buildings/check_in_user"],
        "check_out" => ["status" => 202, "messenger" => "input", "input" => CheckOutUserCommand::class, "output" => false, "method" => "POST", "path" => "/buildings/check_out_user"],
    ],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => ["details"]]],
    ]
)]
#[Entity, Table("building_buildings")]
class Building
{
    /** @var Collection<int, User> */
    #[OneToMany(mappedBy: "building", targetEntity: User::class, cascade: ["PERSIST"])]
    #[Groups("details")]
    public Collection $users;

    public function __construct(
        #[Column, Id]
        #[ApiProperty(identifier: true), Groups(["list", "details"])]
        public string $uuid,

        #[Column, Groups(["list", "details"])]
        public string $name
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