<?php

namespace App\Controller;
use App\Entity\SuperAdmin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Security;


// registerSA
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class SuperAdminController extends AbstractController
{

    /**
     * @Route("/SuperAdmin", name="super_admin_page")
     *
     */
    public function SuperAdminPage(Security $user){
        // SuperAdmin repository
        $SARepo = $this->getDoctrine()->getRepository(SuperAdmin::class)->findAll();

        //Counting records
        $em = $this->getDoctrine()->getManager();
        $RepSA = $em->getRepository(SuperAdmin::class);
        $TotalRots = $RepSA->createQueryBuilder('total')->select('count(total.id)')->getQuery()->getSingleScalarResult();

        return $this->render('SuperAdmin/index.html.twig',array(
        'controller_name' => 'SuperAdminController',
        'RootName' => $user->getUser()->getUsername(),
        'SARepo' => $SARepo,
        'TotalRots' => $TotalRots,
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

    /**
     * @Route("SuperAdmin/registerSA", name="app_registerSA")
     */
    public function registerSA(Request $request, UserPasswordEncoderInterFace $passEmcoder, Security $user)
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' =>['label' => 'Password'],
                'second_options' =>['label' => 'Confirm Password']
            ])
            ->add('Add', SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();

            $user = new SuperAdmin();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passEmcoder->encodePassword($user, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em -> persist($user);
            $em -> flush();
            //przekierowanie do SecurityControllerSuperAdminController
            return $this->redirect($this->generateUrl('super_admin_page'));
        }

    return $this->render('SuperAdmin/CRUD/registerSA.html.twig',array(
        'form' => $form->createView(),
        'controller_name' => 'SuperAdminController_RegisterSA',
        'RootName' => $user->getUser()->getUsername(),
    ));
    }

}