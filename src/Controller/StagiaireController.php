<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StagiaireController extends AbstractController
{
    /**
     * This PHP function retrieves a list of stagiaires (trainees) from the database and renders them
     * in a Twig template.
     * 
     * @param StagiaireRepository stagiaireRepository The `` parameter is an
     * instance of the `StagiaireRepository` class. It is used to retrieve and manipulate data from the
     * database related to the `Stagiaire` entity. In this case, it is used to fetch all the `Stagiaire
     * 
     * @return Response a Response object.
     */
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {   
        $stagiaires = $stagiaireRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    /**
     * This PHP function creates a new Stagiaire (intern) entity using a form, persists it to the
     * database, and redirects to a specified route.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, query parameters, and request body.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting entities, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StagiaireType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();

        
            $entityManager->persist($stagiaire);
            $entityManager->flush();
            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/new.html.twig', [
            'formCreate' => $form->createView()
        ]);
    }


    /**
     * This function is used to edit a stagiaire (intern) entity in a Symfony application.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and query parameters.
     * @param stagiaire stagiaire The parameter "stagiaire" is an instance of the "stagiaire" class. It
     * is used to retrieve the existing stagiaire object from the database, if it exists, and to update
     * it with the submitted form data.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting entities, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function edit(Request $request, stagiaire $stagiaire = null, EntityManagerInterface $entityManager) : Response
    {

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();

            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/new.html.twig',[
            'formCreate' => $form
        ]);
    }

    /**
     * This PHP function deletes a "stagiaire" (intern) entity from the database using Doctrine's
     * EntityManager.
     * 
     * @param stagiaire stagiaire The parameter "stagiaire" is an instance of the "stagiaire" class. It
     * represents the specific stagiaire (intern) object that needs to be deleted.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for performing CRUD (Create, Read, Update, Delete) operations
     * on entities. In this case, it is used to remove the `
     * 
     * @return Response The method is returning a Response object.
     */
    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(stagiaire $stagiaire, EntityManagerInterface $entityManager) : Response
    {   
        $entityManager->remove($stagiaire);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_stagiaire');
    }

    /**
     * The function "show" renders a Twig template for displaying a specific "stagiaire" object.
     * 
     * @param stagiaire stagiaire The parameter "stagiaire" is a type-hinted variable that represents
     * an instance of the "stagiaire" class. It is used to retrieve a specific "stagiaire" object based
     * on the provided "id" in the route.
     * 
     * @return Response a Response object.
     */
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(stagiaire $stagiaire) : Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
}
