<?php

namespace Elevator\ElevatorBundle\Services\ElevatorSystem;

use Elevator\ElevatorBundle\Services\Floor\Floor;
use Elevator\ElevatorBundle\Services\System\System;

class ElevatorPanel
{
    /** @var  System */
    protected $system;

    public function clickButton(Floor $goalFloor)
    {
        $this->system->addNewTask($goalFloor);
    }

    /**
     * @param System $system
     */
    public function setSystem(System $system)
    {
        $this->system = $system;
    }
}