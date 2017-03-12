<?php

namespace Elevator\ElevatorBundle\Tests\Builder;

use Elevator\ElevatorBundle\Services\Builder\Builder;
use Elevator\ElevatorBundle\Services\Floor\Floor;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $builder = new Builder();
        $system = $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 1, 4)
            ->addCargo(Builder::CARGO_PEOPLE, 3, 2)
            ->addCargo(Builder::CARGO_PEOPLE, 4, 1)
            ->create();
        /** @var Floor[] $floorList */
        $floorList = $system->getFloorList();

        $this->assertCount(4, $floorList);

        $this->assertCount(1, $floorList[1]->getCargos());
        $this->assertCount(1, $floorList[3]->getCargos());
        $this->assertCount(1, $floorList[4]->getCargos());

        $this->assertEquals($floorList[1]->getCargos()[0]->getGoalFloor()->getFloorNumber(), 4);
        $this->assertEquals($floorList[3]->getCargos()[0]->getGoalFloor()->getFloorNumber(), 2);
        $this->assertEquals($floorList[4]->getCargos()[0]->getGoalFloor()->getFloorNumber(), 1);
    }

    public function testCreate_5_Floor()
    {
        $builder = new Builder();

        $system = $builder->setFloorCount(5)
            ->create();

        $this->assertCount(5, $system->getFloorList());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGoalFloor()
    {
        $builder = new Builder();
        $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 1, 5)
            ->create();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidStartFloor()
    {
        $builder = new Builder();
        $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 5, 1)
            ->create();
    }
}