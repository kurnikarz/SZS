<?php


namespace App\Controller;


use App\Entity\Member;
use App\Entity\MemberTraining;
use App\Entity\Rating;
use App\Entity\Trainer;
use App\Entity\Training;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
        $rating = $this->getDoctrine()->getRepository(Rating::class)->findOneBy(['member' => $member->getId(), 'training' => $id]);
        $now = new \DateTime();

        if ($rating)
            return new Response("Oceniłeś już to szkolenie!<br><a href='/szkolenia'>Powrót</a>");

        if ($memberTraining) {
            if ($training->getEndDate() < $now) {
                $memberTraining->setFinishedTraining(1);
                $entityManager->persist($memberTraining);
                $entityManager->flush();

                $form = $this->createFormBuilder()
                    ->setAction($this->generateUrl('handleRate',[
                        'idMember' => $member->getId(),
                        'idTraining' => $id
                    ]))
                    ->add('rate', ChoiceType::class, [
                        'label' => 'Ocena: ',
                        'choices' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                        ],'multiple'=>false,'expanded'=>true
                    ])
                    ->add('zapisz', SubmitType::class, [
                        'label' => 'Oceń',
                        'attr' => ['class' => 'btn btn-primary']
                    ])
                    ->getForm();

                return $this->render('training/rate.html.twig', [
                    'nazwaSzkolenia' => $training->getName(),
                    'form' => $form->createView(),
                ]);

            } else {
                return new Response("Jeszcze nie ukończyłeś tego szkolenia!<br><a href='/szkolenia'>Powrót</a>");
            }
        } else
            return new Response("Musisz najpierw zapisać się na szkolenie aby je ocenić!<br><a href='/szkolenia'>Powrót</a>");
    }

    /**
     * @Route("/handleRate/{idMember}{idTraining}", name="handleRate")
     */
    public function handleRate(Request $request, $idMember, $idTraining) {
        $entityManager = $this->getDoctrine()->getManager();
        $rate = $request->request->get('form')['rate'];
        $member = $this->getDoctrine()->getRepository(Member::class)->find($idMember);
        $training = $this->getDoctrine()->getRepository(Training::class)->find($idTraining);
        $ratings = $training->getRatings();
        $sumRatings = 0;
        for ($i=0;$i<count($ratings);$i++)
            $sumRatings += $ratings[$i]->rate;
            //dump($ratings[$i]);
        $rating = new Rating();
        $rating->setMember($member);
        $rating->setTraining($training);
        $rating->setRate($rate);
        if (count($ratings) > 0)
            $training->setRating(($rate+$sumRatings)/(count($ratings)+1));
        else
            $training->setRating($rate);
        $entityManager->persist($rating);
        $entityManager->flush();
        $entityManager->persist($training);
        $entityManager->flush();
        return new Response("Pomyślnie oceniłeś szkolenie!<br><a href='/szkolenia'>Powrót</a>");
    }
}