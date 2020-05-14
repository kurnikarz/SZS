<?php


namespace App\Controller;


use App\Entity\Member;
use App\Entity\MemberTraining;
use App\Entity\Trainer;
use App\Entity\Training;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;

class TrainingController extends AbstractController
{
    /**
     * @Route("/szkolenia", name="training_list", methods={"GET", "HEAD"})
     */
    public function index() {
        $trainings = $this->getDoctrine()->getRepository(Training::class)->findAll();
        return $this->render('training/index.html.twig', array('trainings' => $trainings));
    }

    /**
     * @Route("/szkolenia/{id}", name="training_show")
     */
    public function show($id) {
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $trainer = $training->getTrainer();
        return $this->render('training/show.html.twig', array('training' => $training, 'trainer' => $trainer));
    }

    /**
     * @Route("/szkolenia/join/{id}", name="training_join")
     */
    public function join($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $username = $this->getUser()->getUsername();
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['email' => $username]);
        $memberTraining = new MemberTraining();
        $memberTraining->setTraining($training);
        $memberTraining->setMember($member);

        $entityManager->persist($memberTraining);
        $entityManager->flush();

        return $this->render('training/join.html.twig');
    }
}