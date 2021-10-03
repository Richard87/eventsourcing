<?php

namespace App\Domain\Command;

use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CheckInUserCommandHandler implements MessageHandlerInterface
{
    public function __construct(private BuildingRepository $repo)
    {
    }

    public function __invoke(CheckInUserCommand $command)
    {
        $building = $this->repo->retrieve(Uuid::fromString($command->uuid));
        $building->checkInUser($command->username);
        $this->repo->persist($building);
    }
}