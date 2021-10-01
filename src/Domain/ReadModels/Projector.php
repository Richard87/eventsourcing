<?php

namespace App\Domain\ReadModels;

use App\Domain\DomainEvent\NewBuildingWasRegistered;
use App\Domain\DomainEvent\UserCheckedIn;
use App\Domain\DomainEvent\UserCheckedOut;
use Doctrine\ORM\EntityManagerInterface;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageConsumer;

class Projector implements MessageConsumer
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function handle(Message $message): void
    {
        $event = $message->event();
        if($event instanceof NewBuildingWasRegistered) {
            $this->em->persist(new Building($message->aggregateRootId(), $event->name));
            $this->em->flush();
        }

        if ($event instanceof UserCheckedIn) {
            $building = $this->getBuilding($message);
            $building?->checkIn($event->username);
            $this->em->flush();
        }
        if ($event instanceof UserCheckedOut) {
            $building = $this->getBuilding($message);
            $building?->checkOut($event->username);
            $this->em->flush();
        }

    }

    private function getBuilding(Message $message): ?Building
    {
        return $this->em->getRepository(Building::class)->findOneBy(["uuid" => $message->aggregateRootId()]);
    }
}