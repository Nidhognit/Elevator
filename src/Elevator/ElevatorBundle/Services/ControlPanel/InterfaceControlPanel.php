<?php

namespace Elevator\ElevatorBundle\Services\ControlPanel;

interface InterfaceControlPanel
{
    public function moveUp();

    public function moveDown();

    public function isUp();

    public function isDown();

    public function disable();
}