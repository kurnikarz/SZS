<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class RegisterMemberController extends AbstractController
{
    /**
     * @Route("/register/member", name="register_member")
     */
    public function register()
    {
        $form = $this->createFormBuilder()
            ->add('imie')
            ->add('nazwisko')
            ->add('email')
            ->add('numer_kontaktowy')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password']
            ])
            ->add('rejestruj', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
            ->getForm();

        return $this->render('register_member/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
