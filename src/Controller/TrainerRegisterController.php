<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Trainer;

class TrainerRegisterController extends AbstractController
{
    /**
     * @Route("/registerT", name="trainer_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passEncoder)
    {
       $form = $this->createFormBuilder()
                ->add('username')
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => ['label' => 'password'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
                ->add('register', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ])
                ->getForm();
                

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $data = $form->getData();

            $trainer = new Trainer();
            $trainer->setUsername($data['username']);
            $trainer->setPassword(
                $passEncoder->encodePassword($trainer, $data['password'])
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($trainer);
            $em->flush();

            return $this->redirect($this->generateUrl('trainer'));
        }


        return $this->render('trainer_register/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
