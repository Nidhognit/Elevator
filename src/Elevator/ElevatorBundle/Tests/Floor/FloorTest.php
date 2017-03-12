<?php

namespace Elevator\ElevatorBundle\Tests\Floor;

use Elevator\ElevatorBundle\Services\Cargo\PeopleCargo;
use Elevator\ElevatorBundle\Services\ControlPanel\FullControlPanel;
use Elevator\ElevatorBundle\Services\Floor\Floor;

class FloorTest extends \PHPUnit_Framework_TestCase
{
    public function testMoveUp()
    {
        $currentFloor = new Floor();
        $currentFloor->setFloorNumber(1);
        $currentFloor->setControlPanel(new FullControlPanel());

        $cargo = new PeopleCargo();
        $goalFloor = new Floor();
        $goalFloor->setFloorNumber(5);
        $cargo->setGoalFloor($goalFloor);

        $currentFloor->addCargo($cargo);

        $this->assertTrue($currentFloor->getControlPanel()->isUp());
        $this->assertFalse($currentFloor->getControlPanel()->isDown());
    }

    public function testMoveDown()
    {
        $currentFloor = new Floor();
        $currentFloor->setFloorNumber(10);
        $currentFloor->setControlPanel(new FullControlPanel());

        $cargo = new PeopleCargo();
        $goalFloor = new Floor();
        $goalFloor->setFloorNumber(5);
        $cargo->setGoalFloor($goalFloor);

        $currentFloor->addCargo($cargo);

        $this->assertFalse($currentFloor->getControlPanel()->isUp());
        $this->assertTrue($currentFloor->getControlPanel()->isDown());
    }

    public function testDontMove()
    {
        $currentFloor = new Floor();
        $currentFloor->setFloorNumber(2);
        $currentFloor->setControlPanel(new FullControlPanel());

        $cargo = new PeopleCargo();
        $goalFloor = new Floor();
        $goalFloor->setFloorNumber(2);
        $cargo->setGoalFloor($goalFloor);

        $currentFloor->addCargo($cargo);

        $this->assertFalse($currentFloor->getControlPanel()->isUp());
        $this->assertFalse($currentFloor->getControlPanel()->isDown());
    }

    public function testMove()
    {
        $currentFloor = new Floor();
        $currentFloor->setFloorNumber(2);
        $currentFloor->setControlPanel(new FullControlPanel());

        $cargo = new PeopleCargo();
        $goalFloor = new Floor();
        $goalFloor->setFloorNumber(10);
        $cargo->setGoalFloor($goalFloor);

        $cargo2 = new PeopleCargo();
        $goalFloor2 = new Floor();
        $goalFloor2->setFloorNumber(1);
        $cargo2->setGoalFloor($goalFloor2);

        $currentFloor->addCargo($cargo);
        $currentFloor->addCargo($cargo2);

        $this->assertTrue($currentFloor->getControlPanel()->isUp());
        $this->assertTrue($currentFloor->getControlPanel()->isDown());
    }
}