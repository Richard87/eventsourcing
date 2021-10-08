<?php

namespace App\Domain\ReadModels;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity, Table("building_users")]
class User
{
    #[Column, Id, GeneratedValue]
    public int $id;

    public function __construct(
        #[ManyToOne(cascade: ["PERSIST"], inversedBy: "users"), JoinColumn(referencedColumnName: "uuid", onDelete: "CASCADE")]
        public Building $building,

        #[Column]
        #[Groups("details")]
        public string $name,

        #[Column]
        #[Groups("details")]
        public bool $checkedIn = false,
    )
    {
        $this->building->users->add($this);
    }
}