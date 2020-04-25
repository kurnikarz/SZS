<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Courses;
use App\Entity\Trainer;
use App\Entity\Member;

class CoursesController extends AbstractController{

    /**
     * @Route("/courses", name="courses")
     */
    public function index(){
        $courses = $this->getDoctrine()->getRepository(Courses::class)->findAll();

        return $this->render('courses/courses.html.twig', array('courses' => $courses));
    }
}