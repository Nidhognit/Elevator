<?php

namespace Elevator\ElevatorBundle\Services\Builder;

use Elevator\ElevatorBundle\Services\Cargo\InterfaceCargo;
use Elevator\ElevatorBundle\Services\Cargo\PeopleCargo;
use Elevator\ElevatorBundle\Services\ControlPanel\FullControlPanel;
use Elevator\ElevatorBundle\Services\ControlPanel\InterfaceControlPanel;
use Elevator\ElevatorBundle\Services\ElevatorSystem\Elevator;
use Elevator\ElevatorBundle\Services\ElevatorSystem\ElevatorPanel;
use Elevator\ElevatorBundle\Services\Floor\Floor;
use Elevator\ElevatorBundle\Services\System\System;

class Builder
{
    const CARGO_PEOPLE = 1;

    const CONTROL_PANEL_FULL = 1;

    /** @var int */
    protected $floorCount = 4;

    /** @var float */
    protected $floorHeight = 4;

    protected $typeControlPanel = self::CONTROL_PANEL_FULL;

    /** @var  float */
    protected $elevatorMaxWeight;

    /** @var array */
    protected $cargoList = [];

    /**
     * @param int $floorCount
     * @return $this
     */
    public function setFloorCount(int $floorCount)
    {
        $this->floorCount = $floorCount;

        return $this;
    }

    /**
     * @param float $elevatorMaxWeight
     * @return $this
     */
    public function setElevatorMaxWeight(float $elevatorMaxWeight)
    {
        $this->elevatorMaxWeight = $elevatorMaxWeight;

        return $this;
    }

    /**
     * @param int $cargoType
     * @param int $startFloor
     * @param int $goalFloor
     * @return $this
     */
    public function addCargo(int $cargoType, int $startFloor, int $goalFloor)
    {
        $this->cargoList[] = ['type' => $cargoType, 'startFloor' => $startFloor, 'goalFloor' => $goalFloor];

        return $this;
    }

    /**
     * @return System
     */
    public function create(): System
    {
        /** @var Floor[] $floorList */
        $floorList = [];
        for ($floorNumber = 1; $floorNumber-1 < $this->floorCount; ++$floorNumber) {
            $floor = new Floor();
            $floor->setFloorNumber($floorNumber);
            $floor->setControlPanel($this->getControlPanelByType($this->typeControlPanel));
            $floorList[$floorNumber] = $floor;
        }
        $system = new System();
        $elevator = new Elevator();
        $elevatorPanel = new ElevatorPanel();
        $elevatorPanel->setSystem($system);
        $elevator->setElevatorPanel($elevatorPanel);

        foreach ($this->cargoList as $cargoTask) {
            $cargo = $this->getCargoByType($cargoTask['type']);

            if (!isset($floorList[$cargoTask['startFloor']]) || !isset($floorList[$cargoTask['goalFloor']])) {
                throw new \InvalidArgumentException('You specified an existing floor');
            }

            $cargo->setGoalFloor($floorList[$cargoTask['goalFloor']]);

            $floorList[$cargoTask['startFloor']]->addCargo($cargo);
        }

        $system->setFloorList($floorList);
        $system->setElevator($elevator);
        $system->setCurrentFloor($floorList[1]);

        return $system;
    }

    /**
     * @param int $type
     * @return InterfaceControlPanel
     */
    protected function getControlPanelByType(int $type): InterfaceControlPanel
    {
        switch ($type) {
            case self::CONTROL_PANEL_FULL:
                return new FullControlPanel();
                break;
            default:
                throw new \InvalidArgumentException('Invalid type of control panel');
        }
    }

    /**
     * @param int $type
     * @return InterfaceCargo
     */
    protected function getCargoByType(int $type): InterfaceCargo
    {
        switch ($type) {
            case self::CARGO_PEOPLE:
                return new PeopleCargo();
                break;
            default:
                throw new \InvalidArgumentException('Invalid type of cargo');
        }
    }
}