<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Entity\Training;


class MembersController extends AbstractController
{
    /**
     * @Route("/members", name="members")
     */
    public function index()
    {
        $members = $this->getDoctrine()->getRepository(Member::class)->findAll();
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();
        return $this->render('members/index.html.twig', array('members' => $members,'trainings' => $trainings));
    }
}
