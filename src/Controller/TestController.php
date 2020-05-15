<?php

namespace App\Controller;


use App\Entity\Training;
use App\Repository\MemberTrainingRepository;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test/{id}", name="test")
     */
    public function index(TrainingRepository $trainingRepository, MemberTrainingRepository $memberTrainingRepository,$id)
    {

        $training = $this->getDoctrine()->getRepository(Training::class)->findOneById($id);
        $getMembercourse = $memberTrainingRepository->getCursemember($id);

        return $this->render('test/index.html.twig', [
            'GetMembercourse' =>$getMembercourse,
        ]);
    }
}
