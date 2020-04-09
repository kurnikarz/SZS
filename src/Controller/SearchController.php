<?php

namespace App\Controller;

use App\Entity\Training;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\TrainingRepository;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", methods={"GET"})
     * @return Response
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar() {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('Nazwa', TextType::class)
            ->add('Szukaj',SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
        ])->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch", name="handleSearch")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     */
    public function handleSearch(Request $request) {
        $name = $request->request->get('form')['Nazwa'];
        if ($name) {
            $trainings = $this->getDoctrine()
                ->getRepository(Training::class)
                ->findTrainingByName($name);
            return $this->render("search/result.html.twig", [
                'training' => $trainings
            ]);
        }
        else {
            return new Response("Pole wyszukiwania nie może być puste!<br><a href='search'>Powrót</a>");
        }
    }
}
