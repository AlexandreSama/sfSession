<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(FormationRepository $formationRepository): Response
    {

        $formations = $formationRepository->findAll();
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
            'formations' => $formations
        ]);
    }

    /**
     * The function "new_formation" creates a new Formation object, handles the form submission and
     * validation, persists the formation object to the database, and redirects to the home page.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em The "em" parameter is an instance of the EntityManagerInterface
     * class, which is responsible for managing the persistence of objects in the database. It provides
     * methods for persisting, updating, and deleting objects, as well as querying the database. In
     * this code, it is used to persist the newly created Formation
     * 
     * @return Response a Response object.
     */
    #[Route('/formation/new', name: 'new_formation')]
    public function new_formation(Request $request, EntityManagerInterface $em): Response
    {
        $formation = new Formation();

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $formation = $form->getData();

            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('formation/new.html.twig', [
            'controller_name' => 'FormationController',
            'formCreate' => $form,
        ]);
    }

    /**
     * This function is responsible for editing a formation object and saving the changes to the
     * database.
     * 
     * @param Formation formation The "formation" parameter is an instance of the Formation class. It
     * is used to retrieve the existing formation object that needs to be edited.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, query parameters, and request body.
     * @param EntityManagerInterface em The "em" parameter is an instance of the EntityManagerInterface
     * class, which is responsible for managing the persistence of objects in the database. It provides
     * methods for persisting, updating, and deleting entities, as well as querying the database. In
     * this code, it is used to persist the updated formation object
     * 
     * @return Response a Response object.
     */
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function edit_formation(Formation $formation, Request $request, EntityManagerInterface $em): Response{

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $formation = $form->getData();

            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('formation/new.html.twig', [
            'controller_name' => 'FormationController',
            'formCreate' => $form,
            'edit' => $formation->getId()
        ]);
    }

    #[Route('/formation/{id}', name: 'show_formation')]
    public function show_formation(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }
}
