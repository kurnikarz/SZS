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
use Symfony\Component\HttpFoundation\Response;
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
        $memberTraining->setFinishedTraining(0);

        $entityManager->persist($memberTraining);
        $entityManager->flush();

        return $this->render('training/join.html.twig');
    }


    /**
     * @Route("/szkolenia/ocen/{id}", name="rate_training")
     */
    public function rateTraining($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $username = $this->getUser()->getUsername();
        $training = $this->getDoctrine()->getRepository(Training::class)->find($id);
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['email' => $username]);
        $memberTraining = $this->getDoctrine()->getRepository(MemberTraining::class)->findOneBy(['member' => $member->getId(), 'training' => $id]);
        $now = new \DateTime();

        if ($memberTraining) {
            if ($training->getEndDate() < $now) {
                $memberTraining->setFinishedTraining(1);
                $entityManager->persist($memberTraining);
                $entityManager->flush();

                $form = $this->createFormBuilder()
                    ->add('name', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Imię'])
                    ->add('surname', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Nazwisko'])
                    ->add('number', TextType::class, [ 'attr' => [ 'class' => 'form-control'], 'label' => 'Numer telefonu'])
                    ->add('email', TextType::class, [ 'attr' => [ 'class' => 'form-control']])
                    ->add('zapisz', SubmitType::class, [
                        'label' => 'Zapisz',
                        'attr' => ['class' => 'btn btn-primary']
                    ])
                    ->getForm();

                return $this->render('training/rate.html.twig', [
                    'nazwaSzkolenia' => $training->getName()
                ]);

            } else {
                return new Response("Jeszcze nie ukończyłeś tego szkolenia!<br><a href='/szkolenia'>Powrót</a>");
            }
        } else
            return new Response("Musisz najpierw zapisać się na szkolenie aby je ocenić!<br><a href='/szkolenia'>Powrót</a>");
    }
}