<?php

namespace App\Controller;

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

     public function members(MemberRepository $memberRepository, $id)
     {
         $Smembers = $memberRepository->findMembers();

         $member_training = $this->getDoctrine()->getRepository(MemberTraining::class)->find($id);

         return $this->render('courses/members.html.twig', ['Smembers' => $Smembers, 'member_training' => $member_training]);
     }

    // public function index()
    // {
    //     $members = $this->getDoctrine()->getRepository(Member::class)->findAll();

    //     return $this->render('courses/members.html.twig', array('members' => $members));
    // }
}
