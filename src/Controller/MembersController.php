<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Entity\Training;
use App\Entity\Trainer;


class MembersController extends AbstractController
{
    /**
     * @Route("/members", name="members")
     */
    public function index()
    {
        $trainers = $this->getDoctrine()->getRepository(Trainer::class)->findAll();
        $members = $this->getDoctrine()->getRepository(Member::class)->findAll();
        $training = $this->getDoctrine()->getRepository(Training::class)->findAll();
        return $this->render('members/index.html.twig', array('members' => $members,'training' => $training, 'trainers' => $trainers));
    }
}
