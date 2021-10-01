<?php

namespace App\Domain\Repository;

use App\Domain\Agregate\Building;
use App\Infrastructure\Uuid;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\EventSourcing\UnableToDispatchMessages;
use EventSauce\EventSourcing\UnableToPersistMessages;

class BuildingRepository implements AggregateRootRepository
{
    public function __construct(
        private MessageRepository $messageRepository
    ){}

    public function retrieve(AggregateRootId|string $aggregateRootId): Building
    {
        if (is_string($aggregateRootId))
            $aggregateRootId = Uuid::fromString($aggregateRootId);

        /** @var list<Message> $messages */
        $messages = $this->messageRepository->retrieveAll($aggregateRootId);
        $events = $this->getEventFromMessage($messages);
        return Building::reconstituteFromEvents($aggregateRootId, $events);
    }

    /**
     * @param \Generator<Message> $messages
     * @return \Generator<object>
     */
    private function getEventFromMessage(\Generator $messages): \Generator {
        foreach ($messages as $message) {
            yield $message->event();
        }
    }

    /**
     * @param Building $aggregateRoot
     */
    public function persist(object $aggregateRoot): void
    {
        $events = $aggregateRoot->releaseEvents();
        $messages = array_map(fn(object $object) => new Message($object, [
            Header::AGGREGATE_ROOT_TYPE => Building::class,
            Header::AGGREGATE_ROOT_ID => $aggregateRoot->aggregateRootId()->toString(),
            Header::EVENT_TYPE => $object::class,
            Header::TIME_OF_RECORDING => new \DateTime(),
            Header::AGGREGATE_ROOT_VERSION => $aggregateRoot->aggregateRootVersion(),
        ]), $events);
        $this->messageRepository->persist(...$messages);
    }

    public function persistEvents(AggregateRootId $aggregateRootId, int $aggregateRootVersion, object ...$events): void
    {
        // TODO: Implement persistEvents() method.
    }
}