<?php


namespace App\Controller;


use App\Entity\Member;
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
     * @Route("/szkolenia/new", name="new_training")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $training = new Training();
        $choices = [];
        $trainers = $this->getDoctrine()->getRepository(Trainer::class)->findAll();
        foreach ($trainers as $trainer) {
            array_push($choices, $trainer->getName().' => '.null);
        }

        $form = $this->createFormBuilder($training)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('start_date', DateType::class, array('attr' => array('class' => 'form-control')))
            ->add('end_date', DateType::class, array('attr' => array('class' => 'form-control')))
            ->add('price', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('free', ChoiceType::class, $choices)
            #->add('free', ChoiceType::class, array('attr' => array('class' => 'form-control')))
            #->add('getTrainer', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('getTrainer', ChoiceType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary')
            ))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $training = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('training_list');
        }

        return $this->render('training/new.html.twig', array(
            'form' => $form->createView()
        ));
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
        $member->addTraining($training);

        $entityManager->persist($member);
        $entityManager->flush();
        return $this->render('training/join.html.twig');
    }
}