<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trainer;
use Symfony\Component\Security\Core\Security;

class TrainerController extends AbstractController
{
    /**
     * @Route("/trainer", name="trainer")
     */
    public function index(Security $user)
    {
       // $trainer = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();
                
        return $this->render('trainer/index.html.twig', array(
            'controller_name' => 'TrainerController',
            'trainer' => $user->getUser()->getUsername(),
        ));
    }
}
