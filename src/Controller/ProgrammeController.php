<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use App\Repository\ProgrammeRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammeController extends AbstractController
{
    /**
     * The function "index" in the "ProgrammeController" class retrieves all programmes from the
     * repository and renders the "index.html.twig" template with the programmes as a variable.
     * 
     * @param ProgrammeRepository programmeRepository The `` parameter is an
     * instance of the `ProgrammeRepository` class. It is used to fetch data from the database related
     * to the `Programme` entity. The `findAll()` method is called on the repository to retrieve all
     * the programmes from the database.
     * 
     * @return Response a Response object.
     */
    #[Route('/programme', name: 'app_programme')]
    public function index(ProgrammeRepository $programmeRepository): Response
    {
        $programmes = $programmeRepository->findAll();

        return $this->render('programme/index.html.twig', [
            'controller_name' => 'ProgrammeController',
            'programmes' => $programmes
        ]);
    }

    /**
     * This PHP function creates a new programme object, handles a form submission, retrieves session
     * data, persists the programme object to the database, and redirects to a programme list page.
     * 
     * @param Programme programme The `` parameter is an instance of the `Programme` entity
     * class. It is used to hold the data of the new programme being created.
     * @param Request Request The Request object represents an HTTP request made to the server. It
     * contains information such as the request method, headers, query parameters, and request body.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects to
     * the database. It provides methods for persisting, updating, and deleting objects, as well as
     * querying the database.
     * @param SessionRepository sessionRepository The `sessionRepository` is an instance of the
     * `SessionRepository` class, which is responsible for retrieving and managing session data from
     * the database. It is used in this code to fetch a specific session object based on the
     * `idsession` parameter passed in the route.
     * 
     * @return Response a Response object.
     */
    #[Route('/programme/new/{idsession}', name: 'new_programme')]
    public function new_program(Programme $programme = null, Request $Request, EntityManagerInterface $entityManager, SessionRepository $sessionRepository): Response{
        // Create a new cour object
        $programme = new Programme();

        // Create form by courType to add new cour 
        $form = $this->createForm(ProgrammeType::class, $programme);
        // manage form in relation with de enter request 
        $form->handleRequest($Request);

        // If form is successful submit and valid 
        if ($form->isSubmitted() && $form->isValid()) {

            $sessionid = $Request->attributes->get('idsession');
            $session = $sessionRepository->findBy(['id' => $sessionid])[0];

            // get form data and put it into $cour
            $programme = $form->getData();
            $programme->setSession($session);
            //prepare 
            $entityManager->persist($programme);
            // execute 
            $entityManager->flush();
            // redirect to cour list page
            return $this->redirectToRoute('app_programme');
        }

        return $this->render('programme/new.html.twig', [
            'formCreate' => $form
        ]);
    }

    /**
     * This PHP function is used to edit a programme by creating a form, handling the form submission,
     * and updating the programme in the database.
     * 
     * @param Programme programme The "programme" parameter is an instance of the Programme entity
     * class. It is used to retrieve the existing programme object that needs to be edited. If no
     * programme object is found, it will be set to null.
     * @param Request Request The Request object represents an HTTP request. It contains information
     * about the request such as the request method, headers, query parameters, and request body.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting objects, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/programme/edit/{id}', name: 'edit_programme')]
    public function edit_program(Programme $programme = null, Request $Request, EntityManagerInterface $entityManager): Response
    {
        // Create form by courType to add new cour 
        $form = $this->createForm(ProgrammeType::class, $programme);
        // manage form in relation with de enter request 
        $form->handleRequest($Request);

        // If form is successful submit and valid 
        if ($form->isSubmitted() && $form->isValid()) {

            // get form data and put it into $cour
            $programme = $form->getData();
            //prepare 
            $entityManager->persist($programme);
            // execute 
            $entityManager->flush();
            // redirect to cour list page
            return $this->redirectToRoute('app_programme');
        }

        return $this->render('/programme/edit.html.twig', [
            'formCreate' => $form
        ]);
    }

    /**
     * The function deletes a programme entity from the database and redirects to the 'app_programme'
     * route.
     * 
     * @param Programme programme The "programme" parameter is an instance of the "Programme" entity
     * class. It represents a specific programme object that needs to be deleted.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class. It is responsible for managing the persistence of objects in the
     * database. It provides methods for performing database operations such as inserting, updating,
     * and deleting records.
     * 
     * @return Response The method is returning a Response object.
     */
    #[Route('/programme/{id}/delete', name: 'delete_programme')]
    public function delete(Programme $programme, EntityManagerInterface $entityManager) : Response
    {   
        $entityManager->remove($programme);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_programme');
    }

    /**
     * The function "show" in PHP renders a Twig template to display a specific programme based on its
     * ID.
     * 
     * @param Programme programme The "programme" parameter is an instance of the "Programme" class. It
     * is used to fetch a specific programme object based on the provided "id" in the route. This
     * object is then passed to the "show.html.twig" template for rendering.
     * 
     * @return Response a Response object.
     */
    #[Route('/programme/{id}', name: 'show_programme')]
    public function show(Programme $programme) : Response
    {

        return $this->render('programme/show.html.twig', [
            'programme' => $programme
        ]);
    }

}