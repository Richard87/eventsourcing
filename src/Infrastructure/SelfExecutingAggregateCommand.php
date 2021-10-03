<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\AggregateRootRepository;

interface SelfExecutingAggregateCommand extends AggregateCommand
{

    /**
     * @template T
     * @param callable(T):AggregateRootRepository<T> $getRepo
     * @return mixed
     */
    public function __invoke(callable $getRepo);
}