<?php

namespace Elevator\ElevatorBundle\Controller;

use Elevator\ElevatorBundle\Services\Builder\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $builder = new Builder();
        $system = $builder->setFloorCount(4)
            ->addCargo(Builder::CARGO_PEOPLE, 1, 4)
            ->addCargo(Builder::CARGO_PEOPLE, 3, 2)
            ->addCargo(Builder::CARGO_PEOPLE, 4, 1)
            ->create();

        return $this->render('ElevatorBundle:Default:index.html.twig');
    }
}
