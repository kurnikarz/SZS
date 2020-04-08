<?php

namespace App\Controller;
use App\Entity\SuperAdmin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Security;


class SuperAdminController extends AbstractController
{
    /**
     * @Route("/SuperAdmin", name="super_admin_page")
     *
     */
    public function SuperAdminPage(Security $user){

        $user1 = $this->container->get('security.token_storage')
        ->getToken()->getUser()->getUsername();

        $test = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();

        return $this->render('SuperAdmin/index.html.twig',array(
        'test' => $test,

        'controller_name' => 'SuperAdminController',

        'UserName' => $user->getUser()->getUsername(),
        
        'user1' =>$user1,
		'test' => 'testowe',
        ));
    }
}
