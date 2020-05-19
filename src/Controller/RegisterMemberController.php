<?php

namespace App\Controller;

use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterMemberController extends AbstractController
{
    /**
     * @Route("/register/member", name="register_member")
     */
    public function register(Request $requst, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createFormBuilder()
            ->add('imie')
            ->add('nazwisko')
            ->add('email')
            ->add('numer_kontaktowy')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Hasło'],
                'second_options' => ['label' => 'Powtórz hasło']
            ])
            ->add('rejestruj', SubmitType::class, [
                'label' => 'Zarejestruj się !',
                'attr' => [
                    'class' => 'btn btn-success float-right',
                ]
            ])
            ->getForm();

        $form->handleRequest($requst);
        if ($form->isSubmitted()) {
            $data = $form->getData();

            $member = new Member();
            $member->setName($data['imie']);
            $member->setSurname($data['nazwisko']);
            $member->setEmail($data['email']);
            $member->setNumber($data['numer_kontaktowy']);
            $member->setPassword(
                $passwordEncoder->encodePassword($member, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();

            return $this->redirect($this->generateUrl('app_loginMember'));
        }

        return $this->render('register_member/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
