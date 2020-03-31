<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminController extends AbstractController
{
    /**
     * @Route("/SuperAdmin", name="super_admin")
     */


    public function index(){

        return $this->render('SuperAdmin/index.html.twig',
        [
            'controller_name' => 'SuperAdminController',
        ]);
    }
}
