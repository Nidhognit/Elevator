<?php

namespace Elevator\ElevatorBundle\Services\Cargo;


use Elevator\ElevatorBundle\Services\Floor\Floor;

interface InterfaceCargo
{
    public function getWeight();

    public function setWeight($weight);

    public function getGoalFloor(): Floor;

    public function setGoalFloor(Floor $goalFloor);
}