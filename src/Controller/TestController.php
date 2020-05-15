<?php

namespace App\Controller;


use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(TrainingRepository $trainingRepository)
    {
            $test = $trainingRepository->test();

        return $this->render('test/index.html.twig', [
            'test' => $test
        ]);
    }
}
