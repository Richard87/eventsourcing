<?php

namespace App\Domain\Query;

use App\Domain\DTO\BuildingDetails;
use App\Domain\ReadModels\Building;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class BuildingDetailsQueryHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(BuildingDetailsDomainQuery $query): BuildingDetails
    {
        /** @var Building $building */
        $building = $this->em
            ->getRepository(\App\Domain\ReadModels\Building::class)
            ->findOneBy(["uuid" => $query->uuid]);

        return BuildingDetails::fromBuilding($building);
    }
}