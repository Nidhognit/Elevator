<?php

namespace Elevator\ElevatorBundle\Services\System;

use Elevator\ElevatorBundle\Services\Floor\Floor;

class Task
{
    /** @var  Floor */
    protected $goalFloor;

    /**
     * @return Floor
     */
    public function getGoalFloor(): Floor
    {
        return $this->goalFloor;
    }

    /**
     * @param Floor $goalFloor
     */
    public function setGoalFloor(Floor $goalFloor)
    {
        $this->goalFloor = $goalFloor;
    }
}