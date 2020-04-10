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

        $SARepo = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();
    //Counting records
        $em = $this->getDoctrine()->getManager();
        $RepSA = $em->getRepository(SuperAdmin::class);
        $TotalRots = $RepSA
            ->createQueryBuilder('total')
            ->select('count(total.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('SuperAdmin/index.html.twig',array(
        'SARepo' => $SARepo,

        'controller_name' => 'SuperAdminController',

        'RootName' => $user->getUser()->getUsername(),
        'TotalRots' => $TotalRots,
        
        'user1' =>$user1,
        ));
    }
}
