<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Entity\Training;
use App\Entity\Courses;


class MembersController extends AbstractController
{
    /**
     * @Route("/members", name="members")
     */
    public function index()
    {
        $members = $this->getDoctrine()->getRepository(Member::class)->findAll();
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();
        $courses = $this->getDoctrine()->getRepository(Courses::class)->findAll();
        return $this->render('members/index.html.twig', array('members' => $members,'trainings' => $trainings, 'courses' => $courses));
    }
}
