<?php

namespace App\Domain\ReadModels;

use App\Domain\DomainEvent\NewBuildingWasRegistered;
use App\Domain\DomainEvent\UserCheckedIn;
use App\Domain\DomainEvent\UserCheckedOut;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class Projector implements MessageConsumer
{
    private EntityRepository $buildingRepo;
    private ObjectRepository|EntityRepository $userRepo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->buildingRepo = $this->em->getRepository(Building::class);
        $this->userRepo = $this->em->getRepository(User::class);
    }

    public function handle(Message $message): void
    {
        $event = $message->event();
        if($event instanceof NewBuildingWasRegistered) {
            $this->em->persist(new Building($message->aggregateRootId(), $event->name));
            $this->em->flush();
        }

        if ($event instanceof UserCheckedIn) {
            $building = $this->buildingRepo->findOneBy(["uuid" => $message->aggregateRootId()]);
            $user = $this->userRepo->findOneBy(["building" => $building, "name" => $event->username]);
            $user ??= new User($building,$event->username, true);
            $user->checkedIn = true;

            $this->em->persist($user);
            $this->em->flush();
        }

        if ($event instanceof UserCheckedOut) {
            $building = $this->getBuilding($message);
            $user = $this->userRepo->findOneBy(["building" => $building, "name" => $event->username]);
            if ($user) $user->checkedIn = false;
            $this->em->flush();
        }

    }

    private function getBuilding(Message $message): ?Building
    {
        return $this->buildingRepo->findOneBy(["uuid" => $message->aggregateRootId()]);
    }
}