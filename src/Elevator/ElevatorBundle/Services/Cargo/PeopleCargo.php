<?php
namespace Elevator\ElevatorBundle\Services\Cargo;

use Elevator\ElevatorBundle\Services\Floor\Floor;

class PeopleCargo implements InterfaceCargo
{
    /** @var float */
    protected $weight = 70;

    /** @var  Floor */
    protected $goalFloor;

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

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