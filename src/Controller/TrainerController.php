<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trainer;
use App\Entity\Training;

class TrainerController extends AbstractController
{
    /**
     * @Route("/trainer", name="trainer")
     */
    public function index()
    {
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();

        return $this->render('trainer/index.html.twig', [
            'controller_name' => 'trainer','trainings' => $trainings
        ]);
    }
}
