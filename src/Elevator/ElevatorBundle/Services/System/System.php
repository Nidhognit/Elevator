<?php

namespace Elevator\ElevatorBundle\Services\System;

use Elevator\ElevatorBundle\Services\ElevatorSystem\Elevator;
use Elevator\ElevatorBundle\Services\Floor\Floor;

class System
{
    /** @var Floor[] */
    protected $floorList = [];
    /** @var  Elevator */
    protected $elevator;
    /** @var Task[] */
    protected $taskList = [];
    /** @var  Floor */
    protected $currentFloor;
    /** @var array */
    protected $messageList = [];
    /**
     * @var int
     * -1 move down
     * 0 stop
     * 1 move up
     */
    protected $moveStatus = 0;

    public function run()
    {
        if ($this->isStopInCurrentFloor()) {
            $this->addMessage('Лифт остановился на ' . $this->currentFloor->getFloorNumber() . ' этаже');
            $this->readCurrentFloor();
            $this->removeExecutedTask();
            $this->move();
        } else {
            $this->addMessage('Лифт проехал мимо ' . $this->currentFloor->getFloorNumber() . ' этажа не останавливаясь');
        }
        $this->choiceNextFloor();

        return !empty($this->taskList);
    }

    public function readFloorControlPanel()
    {
        foreach ($this->floorList as $floor) {
            $controlPanel = $floor->getControlPanel();
            if ($controlPanel->isUp()) {
                $this->addMessage('На этаже номер ' . $floor->getFloorNumber() . '  нажата кнопка вверх');
            }
            if ($controlPanel->isDown()) {
                $this->addMessage('На этаже номер ' . $floor->getFloorNumber() . '  нажата кнопка вниз');
            }
            if ($controlPanel->isUp() || $controlPanel->isDown()) {
                $this->addNewTask($floor);
            }
        }
    }

    protected function readCurrentFloor()
    {
        $controlPanel = $this->currentFloor->getControlPanel();

        $this->elevator->OpenDor();
        $this->addMessage('Двери лифта открываются');

        $elevatorCargos = $this->elevator->getCargos();
        foreach ($elevatorCargos as $cargo) {
            if ($cargo->getGoalFloor()->getFloorNumber() === $this->currentFloor->getFloorNumber()) {
                $this->addMessage('Человек выходит из лифта на ' . $this->currentFloor->getFloorNumber() . ' этаж и тут же убегает, т.к. возвращение в лифт не предусмотрено');
            }
        }
        $this->elevator->removeCargoByFloor($this->currentFloor);

        $cargos = $this->currentFloor->getCargos();
        $this->currentFloor->setCargos([]);

        foreach ($cargos as $cargo) {
            $this->elevator->addCargo($cargo);
            $this->addMessage('Человек заходит в лифт и нажимает кнопку ' . $cargo->getGoalFloor()->getFloorNumber() . ' этажа ');
        }

        $this->elevator->closeDor();
        $this->addMessage('Двери лифта закрываются');

        $controlPanel->disable();

    }

    protected function removeExecutedTask()
    {
        foreach ($this->taskList as $key => $task) {
            if ($task->getGoalFloor()->getFloorNumber() == $this->currentFloor->getFloorNumber()) {
                unset($this->taskList[$key]);
            }
        }
    }

    protected function isStopInCurrentFloor()
    {
        $controlPanel = $this->currentFloor->getControlPanel();

        return ($this->moveStatus >= 0 && $controlPanel->isUp()) || ($this->moveStatus <= 0 && $controlPanel->isDown()) || $this->isCargoWantGoOut();
    }

    protected function move()
    {
        if (!empty($this->taskList)) {
            $task = reset($this->taskList);
            if ($task->getGoalFloor()->getFloorNumber() > $this->currentFloor->getFloorNumber()) {
                $this->moveStatus = 1;
                $this->addMessage('Лифт отьехал от ' . $this->currentFloor->getFloorNumber() . ' этажа вверх');
            } else {
                $this->moveStatus = -1;
                $this->addMessage('Лифт отьехал от ' . $this->currentFloor->getFloorNumber() . ' этажа вниз');
            }
        } else {
            $this->addMessage('Лифт стоит');
            $this->moveStatus = 0;
        }

    }

    protected function choiceNextFloor()
    {
        $this->currentFloor = $this->floorList[$this->currentFloor->getFloorNumber() + $this->moveStatus];
    }

    protected function isCargoWantGoOut(): bool
    {
        foreach ($this->elevator->getCargos() as $cargo) {
            if ($cargo->getGoalFloor()->getFloorNumber() == $this->currentFloor->getFloorNumber()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Floor[]
     */
    public function getFloorList(): array
    {
        return $this->floorList;
    }

    /**
     * @param Floor[] $floorList
     */
    public function setFloorList(array $floorList)
    {
        $this->floorList = $floorList;
    }

    /**
     * @return Elevator
     */
    public function getElevator(): Elevator
    {
        return $this->elevator;
    }

    /**
     * @param Elevator $elevator
     */
    public function setElevator(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    /**
     * @return Task[]
     */
    public function getTaskList(): array
    {
        return $this->taskList;
    }

    /**
     * @param Task[] $taskList
     */
    public function setTaskList(array $taskList)
    {
        $this->taskList = $taskList;
    }

    /**
     * @param Floor $goalFloor
     */
    public function addNewTask(Floor $goalFloor)
    {
        $task = new Task();
        $task->setGoalFloor($goalFloor);
        $this->taskList[] = $task;
    }

    /**
     * @return Floor
     */
    public function getCurrentFloor(): Floor
    {
        return $this->currentFloor;
    }

    /**
     * @param Floor $currentFloor
     */
    public function setCurrentFloor(Floor $currentFloor)
    {
        $this->currentFloor = $currentFloor;
    }

    /**
     * @return array
     */
    public function getMessageList(): array
    {
        $messageList = $this->messageList;
        $this->messageList = [];

        return $messageList;
    }

    public function addMessage($message)
    {
        $this->messageList[] = $message;
    }

}