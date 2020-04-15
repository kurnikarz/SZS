<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Courses;
use Symfony\Component\Security\Core\Security;

class CoursesController extends AbstractController
{
    /**
     * @Route("/courses", name="courses")
     */
    public function index(Security $user)
    {

        return $this->render('courses/index.html.twig', array(
            'controller_name' => 'CoursesController',
            'courses' => $user->getUser()
        ));
    }
}
//comment
