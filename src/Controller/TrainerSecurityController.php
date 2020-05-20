<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Trainer;

class TrainerSecurityController extends AbstractController
{
    /**
     * @Route("/loginT", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/trainer/password", name="trainer_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $username = $this->getUser()->getUsername();
        $trainer = $this->getDoctrine()->getRepository(Trainer::class)->findOneBy(['username' => $username]);

        $formPass = $this->createFormBuilder()
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'New password'],
                'second_options' => ['label' => 'Reapeat new password']
            ])
            ->add('Save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        $formPass->handleRequest($request);

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $data = $formPass->getData();
            $newPass = $passwordEncoder->encodePassword($trainer, $data['password']);
            $this->getDoctrine()->getRepository(Trainer::class)->upgradePassword($trainer,$newPass);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new Response("Password changed !<br><a href='/trainer'>Back</a>");
        }
        return $this->render('trainer_edit/index.html.twig', [
            'form' => $formPass->createView()
        ]);
    }
}
