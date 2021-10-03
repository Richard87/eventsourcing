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

class CommandBusHandler implements MessageHandlerInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $commandBus,
        private MessageRepository $messageRepository,
        private MessageDispatcher $dispatcher,
        private ?ClassNameInflector $classNameInflector = null,
        private ?MessageDecorator $decorator = null,
    )
    {
        $this->messageBus = $commandBus;
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
    public function __invoke(SelfExecutingAggregateCommand $command)
    {
        $className  = $command->getClassname();
        $repository = $this->getRepo($className);

        return $command($repository);
    }

    private function getRepo(string $className): AggregateRootRepository
    {
        return new EventSourcedAggregateRootRepository(
            $className, $this->messageRepository, $this->dispatcher, $this->decorator, $this->classNameInflector
        );
    }
}