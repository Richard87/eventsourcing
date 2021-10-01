<?php

namespace App\Domain\Repository;

use App\Domain\Agregate\Building;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;

/**
 * @extends EventSourcedAggregateRootRepository<Building>
 */
class BuildingRepository extends EventSourcedAggregateRootRepository
{
    public function __construct(MessageRepository $messageRepository, MessageDispatcher $dispatcher = null, MessageDecorator $decorator = null, ClassNameInflector $classNameInflector = null)
    {
        parent::__construct(Building::class, $messageRepository, $dispatcher, $decorator, $classNameInflector);
    }
}