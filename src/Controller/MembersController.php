<?php

namespace App\Controller;

use App\Repository\MemberTrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Entity\Training;
use App\Entity\Courses;
use App\Entity\MemberTraining;
use App\Entity\Trainer;
use App\Repository\MemberRepository;

class MembersController extends AbstractController
{
    /**
     * @Route("/course/{id}", name="members")
     */

     public function members($id, MemberTrainingRepository $memberTrainingRepository )
     {
         $training = $this->getDoctrine()->getRepository(Training::class)->findOneById($id);
         $getMembercourse = $memberTrainingRepository->getCursemember($id);

         return $this->render('courses/members.html.twig', [
            'GetMembercourse' => $getMembercourse,
             'training' => $training,
         ]);
     }

    // public function index()
    // {
    //     $members = $this->getDoctrine()->getRepository(Member::class)->findAll();

    //     return $this->render('courses/members.html.twig', array('members' => $members));
    // }
}
