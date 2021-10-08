<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\ClassNameInflector;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\MessageDecorator;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class CommandBusHandler implements MessageHandlerInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus,
        private MessageRepository $messageRepository,
        private MessageDispatcher $dispatcher,
        private Stopwatch $stopwatch,
        private ?ClassNameInflector $classNameInflector = null,
        private ?MessageDecorator $decorator = null,
    )
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @template T
     * @psalm-param T $command
     * @psalm-return T::__invoke
     */
    public function execute(AggregateCommand $command)
    {
        return $this->handle($command);
    }

    /**
     * @internal
     */
    public function __invoke(SelfExecutingAggregateCommand|AggregateExecutedCommand $command)
    {
        if ($command instanceof SelfExecutingAggregateCommand) {
            $getRepo = fn($className) => $this->getRepo($className);
            return $command($getRepo);
        }

        $reflection = new \ReflectionClass($command);
        $attributes = $reflection->getAttributes(HandledByAggregate::class);
        $commandClass      = $command::class;
        if (count($attributes) !== 1) {
            throw new \DomainException("$commandClass is missing required attribute 'HandledByAggregate'!");
        }

        /** @var HandledByAggregate $attribute */
        $attribute = $attributes[0]->newInstance();

        $className = $attribute->className;
        $method = $attribute->method;
        $constructor = $attribute->constructor;
        if (!$method && !$constructor) {
            throw new \DomainException("Method or constructor is required in '$commandClass'!");
        }
        if ($method && $constructor) {
            throw new \DomainException("Either Method or constructor must be set, not both in '$commandClass'!");
        }

        $repo = $this->getRepo($attribute->className);
        if ($method) {
            $identifier = $attribute->identifier;
            if (!property_exists($command, $identifier)) {
                throw new \DomainException("When method is used, the command must have a property '$identifier' in '$commandClass'!");
            }
            if (!method_exists($className, $method)) {
                throw new \DomainException("When method is used, the aggregate ('$className') must have a method '$method' in '$commandClass'!");
            }
            $uuid      = Uuid::fromString($command->$identifier);
            $aggregate = $repo->retrieve($uuid);

            $result = $aggregate->$method($command);
            $repo->persist($aggregate);
            return $result;
        }

        if (! method_exists($attribute->className, $constructor)) {
            throw new \DomainException("When constructor is used, the aggregate ('$className') must have a constructor '$constructor' in '$commandClass'!");
        }

        /** @var AggregateRoot $aggregate */
        $aggregate = $className::$constructor($command);
        $repo->persist($aggregate);
        return $aggregate->aggregateRootId()->toString();
    }

    private function getRepo(string $className): AggregateRootRepository
    {
        return new EventSourcedAggregateRootRepository(
            $className, $this->messageRepository, $this->dispatcher, $this->decorator, $this->classNameInflector,$this->stopwatch
        );
    }
}