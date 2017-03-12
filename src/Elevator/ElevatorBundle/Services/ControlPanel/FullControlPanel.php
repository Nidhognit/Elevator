<?php

namespace Elevator\ElevatorBundle\Services\ControlPanel;


class FullControlPanel implements InterfaceControlPanel
{
    protected $isUp = false;
    protected $isDown = false;

    public function moveUp()
    {
        $this->isUp = true;
    }

    public function moveDown()
    {
        $this->isDown = true;
    }

    /**
     * @return bool
     */
    public function isUp(): bool
    {
        return $this->isUp;
    }

    /**
     * @return bool
     */
    public function isDown(): bool
    {
        return $this->isDown;
    }

    public function disable()
    {
        $this->isUp = false;
        $this->isDown = false;
    }

}