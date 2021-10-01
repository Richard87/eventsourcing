<?php

namespace App\Tests;

use App\Domain\Agregate\Building;
use App\Domain\DomainEvent\NewBuildingWasRegistered;
use App\Domain\DomainEvent\UserCheckedIn;
use App\Domain\DomainEvent\UserCheckedOut;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{

    public function testCheckoutUser()
    {
        $building = Building::new("Slottet");
        $building->checkInUser("Richard");
        $building->checkOutUser("Richard");
        $events = $building->releaseEvents();
        self::assertEquals([
            new NewBuildingWasRegistered("Slottet"),
            new UserCheckedIn("Richard"),
            new UserCheckedOut("Richard"),
        ], $events);

        self::assertContainsEquals(new UserCheckedIn("Richard"), $events);

        $this->expectException(\DomainException::class);
        $building->checkOutUser("Richard");
    }
}