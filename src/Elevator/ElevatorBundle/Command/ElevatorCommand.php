<?php
namespace Elevator\ElevatorBundle\Command;

use Elevator\ElevatorBundle\Services\Builder\Builder;
use Elevator\ElevatorBundle\Services\System\System;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ElevatorCommand extends ContainerAwareCommand
{
    /** @var  System */
    private $system;
    /** @var  OutputInterface */
    private $output;

    protected function configure()
    {
        $this->setName('elevator:run')
            ->setDescription('Simulation of the elevator operation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $builder = new Builder();
        $this->system = $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 1, 4)
            ->addCargo(Builder::CARGO_PEOPLE, 3, 2)
            ->addCargo(Builder::CARGO_PEOPLE, 4, 1)
            ->create();

        $this->system->readFloorControlPanel();
        $this->showMessage();

        $run = true;
        $count = 0;

        while ($run) {
            $run = $this->system->run();
            $this->showMessage();

            ++$count;
            if ($count >= 100) {
                $run = false;
            }
        }

        $output->writeln('All tasks completed');
    }

    protected function showMessage()
    {
        $messageList = $this->system->getMessageList();
        if (!empty($messageList)) {
            foreach ($messageList as $message) {
                $this->output->writeln($message);
            }
        } else {
            $this->output->writeln('Ничего не происходит');
        }
    }

}