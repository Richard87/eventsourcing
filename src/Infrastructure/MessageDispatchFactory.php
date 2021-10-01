<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\SynchronousMessageDispatcher;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ServiceLocator;

class MessageDispatchFactory
{
    public function __construct(private $consumers)
    {
    }

    public function create(): MessageDispatcher {
        $consumers = [];
        foreach ($this->consumers as $c)
            $consumers[] = $c;

        return new SynchronousMessageDispatcher(...$consumers);
    }
}