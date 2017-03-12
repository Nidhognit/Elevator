<?php

namespace Elevator\ElevatorBundle\Services\ElevatorSystem;

use Elevator\ElevatorBundle\Services\Cargo\InterfaceCargo;
use Elevator\ElevatorBundle\Services\Floor\Floor;

class Elevator
{
    /** @var  float */
    protected $maxWeight;
    /** @var float */
    protected $speed = 1;

    /** @var InterfaceCargo[] */
    protected $cargos = [];

    protected $isOpenDor = false;
    /** @var  ElevatorPanel */
    protected $elevatorPanel;

    /**
     * @return bool
     */
    public function move(): bool
    {
        $status = $this->isOpenDor;

        if ($this->maxWeight !== null) {
            $weight = 0;
            foreach ($this->cargos as $cargo) {
                $weight += $cargo->getWeight();
            }

            $status *= $this->maxWeight > $weight;
        }

        return $status;
    }

    /**
     * @return float
     */
    public function getMaxWeight(): float
    {
        return $this->maxWeight;
    }

    /**
     * @param float $maxWeight
     */
    public function setMaxWeight(float $maxWeight)
    {
        $this->maxWeight = $maxWeight;
    }

    /**
     * @return InterfaceCargo[]
     */
    public function getCargos(): array
    {
        return $this->cargos;
    }

    /**
     * @param InterfaceCargo[] $cargos
     */
    public function setCargos(array $cargos)
    {
        $this->cargos = $cargos;
    }

    /**
     * @param InterfaceCargo $cargo
     */
    public function addCargo(InterfaceCargo $cargo)
    {
        $this->elevatorPanel->clickButton($cargo->getGoalFloor());
        $this->cargos[] = $cargo;
    }

    public function removeCargoByFloor(Floor $floor)
    {
        foreach ($this->cargos as $key => $cargo) {
            if ($cargo->getGoalFloor()->getFloorNumber() === $floor->getFloorNumber()) {
                unset($this->cargos[$key]);
            }
        }
    }

    /**
     * @return float
     */
    public function getSpeed(): float
    {
        return $this->speed;
    }

    /**
     * @param float $speed
     */
    public function setSpeed(float $speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return bool
     */
    public function isOpenDor(): bool
    {
        return $this->isOpenDor;
    }


    public function OpenDor()
    {
        $this->isOpenDor = true;
    }

    public function closeDor()
    {
        $this->isOpenDor = false;
    }

    /**
     * @param ElevatorPanel $elevatorPanel
     */
    public function setElevatorPanel(ElevatorPanel $elevatorPanel)
    {
        $this->elevatorPanel = $elevatorPanel;
    }
}