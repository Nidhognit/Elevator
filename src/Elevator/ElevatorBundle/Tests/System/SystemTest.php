<?php

namespace Elevator\ElevatorBundle\Tests\System;

use Elevator\ElevatorBundle\Services\Builder\Builder;

class SystemTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfiguration()
    {
        $builder = new Builder();
        $system = $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 1, 4)
            ->addCargo(Builder::CARGO_PEOPLE, 3, 2)
            ->addCargo(Builder::CARGO_PEOPLE, 4, 1)
            ->create();

        $system->readFloorControlPanel();

        $run = true;
        $count = 0;

        while ($run) {
            $run = $system->run();

            ++$count;
            if ($count >= 100) {
                $run = false;
            }
        }

        $this->assertEquals($count, 7);
        $message = $system->getMessageList();
        $this->assertEquals($message, $this->defaultMessage);
    }

    protected $defaultMessage = [
        'На этаже номер 1  нажата кнопка вверх',
        'На этаже номер 3  нажата кнопка вниз',
        'На этаже номер 4  нажата кнопка вниз',
        'Лифт остановился на 1 этаже',
        'Двери лифта открываются',
        'Человек заходит в лифт и нажимает кнопку 4 этажа',
        'Двери лифта закрываются',
        'Лифт отьехал от 1 этажа вверх',
        'Лифт проехал мимо 2 этажа не останавливаясь',
        'Лифт проехал мимо 3 этажа не останавливаясь',
        'Лифт остановился на 4 этаже',
        'Двери лифта открываются',
        'Человек выходит из лифта на 4 этаж и тут же убегает, т.к. возвращение в лифт не предусмотрено',
        'Человек заходит в лифт и нажимает кнопку 1 этажа',
        'Двери лифта закрываются',
        'Лифт отьехал от 4 этажа вниз',
        'Лифт остановился на 3 этаже',
        'Двери лифта открываются',
        'Человек заходит в лифт и нажимает кнопку 2 этажа',
        'Двери лифта закрываются',
        'Лифт отьехал от 3 этажа вниз',
        'Лифт остановился на 2 этаже',
        'Двери лифта открываются',
        'Человек выходит из лифта на 2 этаж и тут же убегает, т.к. возвращение в лифт не предусмотрено',
        'Двери лифта закрываются',
        'Лифт отьехал от 2 этажа вниз',
        'Лифт остановился на 1 этаже',
        'Двери лифта открываются',
        'Человек выходит из лифта на 1 этаж и тут же убегает, т.к. возвращение в лифт не предусмотрено',
        'Двери лифта закрываются',
        'Лифт стоит'
    ];
}