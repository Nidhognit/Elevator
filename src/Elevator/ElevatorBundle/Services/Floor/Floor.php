<?php

namespace Elevator\ElevatorBundle\Services\Floor;

use Elevator\ElevatorBundle\Services\Cargo\InterfaceCargo;
use Elevator\ElevatorBundle\Services\ControlPanel\InterfaceControlPanel;

class Floor
{
    /** @var  InterfaceControlPanel */
    protected $controlPanel;

    /** @var InterfaceCargo[] */
    protected $cargos = [];

    /** @var float */
    protected $height = 4;

    /** @var  int */
    protected $floorNumber;

    /**
     * @return InterfaceControlPanel
     */
    public function getControlPanel()
    {
        return $this->controlPanel;
    }

    /**
     * @param InterfaceControlPanel $controlPanel
     */
    public function setControlPanel($controlPanel)
    {
        $this->controlPanel = $controlPanel;
    }

    /**
     * @return InterfaceCargo[]
     */
    public function getCargos(): array
    {
        return $this->cargos;
    }

    /**
     * @param InterfaceCargo|array $cargos
     */
    public function setCargos($cargos)
    {
        foreach ($cargos as $cargo) {
            $this->clickControlPanel($cargo);
        }
        $this->cargos = $cargos;
    }

    /**
     * @param InterfaceCargo $cargo
     */
    public function addCargo(InterfaceCargo $cargo)
    {
        $this->clickControlPanel($cargo);
        $this->cargos[] = $cargo;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getFloorNumber(): int
    {
        return $this->floorNumber;
    }

    /**
     * @param int $floorNumber
     */
    public function setFloorNumber(int $floorNumber)
    {
        $this->floorNumber = $floorNumber;
    }

    protected function clickControlPanel(InterfaceCargo $cargo)
    {
        if ($cargo->getGoalFloor()->getFloorNumber() > $this->floorNumber) {
            $this->controlPanel->moveUp();
        } elseif ($cargo->getGoalFloor()->getFloorNumber() < $this->floorNumber) {
            $this->controlPanel->moveDown();
        }
    }
}