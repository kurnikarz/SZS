<?php

namespace App\Controller;

use App\Entity\Member;
#use Doctrine\DBAL\Types\TextType;
#use http\Env\Request;
use App\Entity\MemberTraining;
use App\Entity\Training;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController
{
    /**
     * @Route("/profil", name="member")
     */
    public function index(Request $request)
    {
        $username = $this->getUser()->getUsername();
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['email' => $username]);
        $memberTraining = $this->getDoctrine()->getRepository(MemberTraining::class)->findBy(['member' => $member->getId()]);
        $trainings = Array();

        foreach ($memberTraining as $i => $training) {
            array_push($trainings, $memberTraining[$i]->getTraining());
        }

        $form = $this->createFormBuilder($member)
            ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Imię'])
            ->add('surname', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Nazwisko'])
            ->add('number', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Numer telefonu'])
            ->add('email', TextType::class, [ 'attr' => [ 'class' => 'form-control']])
            ->add('zapisz', SubmitType::class, [
                'label' => 'Zapisz',
                'attr' => ['class' => 'btn btn-primary']
                ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $success = true;

            return $this->redirectToRoute('member');
        }

        return $this->render('member/index.html.twig', [
            'form' => $form->createView(),
            'trainings' => $trainings
        ]);
    }

    /**
     * @Route("/removeTraining/{id}", name="remove_training")
     * Method({"GET"})
     */
    public function removeTraining($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $username = $this->getUser()->getUsername();
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['email' => $username]);
        $memberTraining = $this->getDoctrine()->getRepository(MemberTraining::class)->findBy(['member' => $member->getId()]);

        for ($i=0;$i<count($memberTraining);$i++){
            if ($memberTraining[$i]->getTraining() == $training)
                $member->removeMemberTraining($memberTraining[$i]);
        }

        $entityManager->persist($member);
        $entityManager->flush();
        return $this->render('training/remove.html.twig');
    }
}
