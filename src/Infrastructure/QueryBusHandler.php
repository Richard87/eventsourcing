<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBusHandler
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $queryBus,
        private MessageRepository $messageRepository,
        private MessageDispatcher $dispatcher,
        private ?ClassNameInflector $classNameInflector = null,
        private ?MessageDecorator $decorator = null,
    )
    {
        $this->messageBus = $queryBus;
    }

    public function query(DomainQuery $query)
    {
        return $this->handle($query);
    }
}