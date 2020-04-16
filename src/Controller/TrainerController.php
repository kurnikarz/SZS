<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trainer;

class TrainerController extends AbstractController
{
    /**
     * @Route("/trainer", name="trainer")
     */
    public function index()
    {
        return $this->render('trainer/index.html.twig', [
            'controller_name' => 'Trainer',
        ]);
    }
}
