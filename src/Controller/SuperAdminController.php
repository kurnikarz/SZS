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
        // SuperAdmin repository
        $SARepo = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();

        //Get_Root_Name
        $user1 = $this->container->get('security.token_storage')
        ->getToken()->getUser()->getUsername();

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

    /**
     * @Route("SuperAdmin/crudSA", name="super_admin_crud")
     *
     */
    public function SuperAdminPage_CRUD(Security $user){
        //Get_Root_Name
        $user1 = $this->container->get('security.token_storage')
        ->getToken()->getUser()->getUsername();

    return $this->render('SuperAdmin/CRUD/crud.html.twig',array(
        'controller_name' => 'SuperAdminController_CRUD',
        'RootName' => $user->getUser()->getUsername(),
        ));
    }

}
