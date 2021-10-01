<?php

namespace App\Domain\ReadModels;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table("building_users")]
class User
{
    #[Column, Id, GeneratedValue]
    public int $id;

    public function __construct(
        #[ManyToOne(cascade: ["PERSIST"]), JoinColumn(onDelete: "CASCADE", referencedColumnName: "uuid")]
        public Building $building,

        #[Column]
        public string $name,

        #[Column]
        public bool $checkedIn = false,
    )
    {
        $this->building->users->add($this);
    }
}