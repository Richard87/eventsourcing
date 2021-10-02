<?php

namespace App\Domain\Query;

use App\Domain\DTO\BuildingsList;
use App\Domain\ReadModels\Building;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ListBuildingsQueryHandler implements MessageHandlerInterface
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(ListBuildingsQuery $query): BuildingsList
    {
        /** @var Building[] $buildings */
        $buildings = $this->em->getRepository(\App\Domain\ReadModels\Building::class)->findAll();
        $list = [];
        foreach ($buildings as $b)
            $list[$b->uuid] = $b->name;

        return new BuildingsList($list);
    }
}