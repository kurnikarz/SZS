<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\SuperAdmin;

class RegisterSAController extends AbstractController
{
    /**
     * @Route("/registerSA", name="app_registerSA")
     */
    public function registerSA(Request $request, UserPasswordEncoderInterFace $passEmcoder)
    {
        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' =>['label' => 'Password'],
                'second_options' =>['label' => 'Confirm Password']
            ])
            ->add('register', SubmitType::class,
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

            return $this->redirect($this->generateUrl('app_loginSA'));
        }

    return $this->render('registerSA/registerSA.html.twig',[
        'form' => $form->createView()
    ]);
    }
}
