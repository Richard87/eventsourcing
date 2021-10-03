<?php

namespace App\Infrastructure;

use EventSauce\EventSourcing\AggregateRootRepository;

interface SelfExecutingAggregateCommand extends AggregateCommand
{

    public function getClassname(): string;

    public function __invoke(AggregateRootRepository $repo);
}