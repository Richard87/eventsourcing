<?php

namespace App\Domain\Agregate;

use App\Domain\DomainEvent\NewBuildingWasRegistered;
use App\Domain\DomainEvent\UserCheckedIn;
use App\Domain\DomainEvent\UserCheckedOut;
use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Symfony\Component\Uid\Uuid;

class Building implements AggregateRoot
{
    use AggregateRootBehaviour;

    private Uuid $uuid;
    public ?string $name = null;
    /** @var array<string, bool> */
    public array $users = [];

    public static function new(string $name) : self
    {
        $self = new self(\App\Infrastructure\Uuid::create());

        $self->recordThat(new NewBuildingWasRegistered($name));

        return $self;
    }

    protected function applyNewBuildingWasRegistered(NewBuildingWasRegistered $event)
    {
        $this->name = $event->name;
    }

    public function checkInUser(string $username): void
    {
        if ($this->isCheckedIn($username))
            throw new \DomainException("Can't check in user that is already checked in!");

        $this->recordThat(new UserCheckedIn($username));
    }

    protected function applyUserCheckedIn(UserCheckedIn $event) {

        $this->users[$event->username] = true;
    }

    public function checkOutUser(string $username): void
    {
        if (!$this->isCheckedIn($username))
            throw new \DomainException("Can't check out user that is not checked in!");

        $this->recordThat(new UserCheckedOut($username));
    }
    protected function applyUserCheckedOut(UserCheckedOut $event): void {
        $this->users[$event->username] = false;
    }

    private function isCheckedIn(string $username): bool {
        return $this->users[$username] ?? false;
    }
}