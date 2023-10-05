<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Form\CourType;
use App\Repository\CourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{

    /**
     * The function retrieves a list of courses from the CourRepository and renders them in a Twig
     * template.
     * 
     * @param CourRepository courRepository The `` parameter is an instance of the
     * `CourRepository` class. It is used to fetch data from the database related to the `Cour` entity.
     * 
     * @return Response a Response object.
     */
    #[Route('/cour', name: 'app_cour')]
    public function index(CourRepository $courRepository): Response
    {
        $cours = $courRepository->findBy([], ['nomCour' => 'ASC']);

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    /**
     * This PHP function creates a new "cour" object, handles a form submission, persists the data to
     * the database, and redirects to a cour list page.
     * 
     * @param cour cour The parameter "cour" is an instance of the "cour" class. It is used to create a
     * new object of the "cour" class.
     * @param Request Request The Request object represents an HTTP request. It contains information
     * about the request such as the request method, headers, query parameters, and request body.
     * @param EntityManagerInterface entityManager The `entityManager` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting objects, as well as
     * querying the database. In this code, the `entityManager` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/cour/new', name: 'new_cour')]
    public function new(cour $cour = null, Request $Request, EntityManagerInterface $entityManager): Response
    {
        // Create a new cour object
        $cour = new cour();

        // Create form by courType to add new cour 
        $form = $this->createForm(courType::class, $cour);
        // manage form in relation with de enter request 
        $form->handleRequest($Request);

        // If form is successful submit and valid 
        if ($form->isSubmitted() && $form->isValid()) {

            // get form data and put it into $cour
            $cour = $form->getData();
            //prepare 
            $entityManager->persist($cour);
            // execute 
            $entityManager->flush();
            // redirect to cour list page
            return $this->redirectToRoute('app_cour');
        }

        return $this->render('cour/new.html.twig', [
            'formCreate' => $form
        ]);
    }

    /**
     * This PHP function handles the editing of a "cour" object, including form validation and
     * persistence to the database.
     * 
     * @param cour cour The parameter "cour" is an instance of the "cour" class. It is used to retrieve
     * the existing "cour" object from the database if an ID is provided in the route. If no ID is
     * provided or the "cour" object does not exist, it will be set to null.
     * @param Request Request The Request object represents an HTTP request. It contains information
     * about the request such as the request method, headers, query parameters, and request body.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting objects, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/cour/{id}/edit', name: 'edit_cour')]
    public function edit(cour $cour = null, Request $Request, EntityManagerInterface $entityManager): Response
    {
        // Create form by courType to add new cour 
        $form = $this->createForm(CourType::class, $cour);
        // manage form in relation with de enter request 
        $form->handleRequest($Request);

        // If form is successful submit and valid 
        if ($form->isSubmitted() && $form->isValid()) {

            // get form data and put it into $cour
            $cour = $form->getData();
            //prepare 
            $entityManager->persist($cour);
            // execute 
            $entityManager->flush();
            // redirect to cour list page
            return $this->redirectToRoute('app_cour');
        }

        return $this->render('/cour/new.html.twig', [
            'formCreate' => $form
        ]);
    }

    /**
     * The above function deletes a "cour" object and its associated "programmes" from the database.
     * 
     * @param cour cour The parameter "cour" is an instance of the "cour" entity class. It represents a
     * specific course object that needs to be deleted.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for performing CRUD (Create, Read, Update, Delete) operations
     * on entities.
     * @param CourRepository courRepository The `` parameter is an instance of the
     * `CourRepository` class, which is responsible for retrieving and manipulating data related to the
     * `cour` entity. It is used in this method to find all the programs associated with the given
     * `` object.
     * 
     * @return Response a Response object.
     */
    #[Route('/cour/{id}/delete', name: 'delete_cour')]
    public function delete(cour $cour, EntityManagerInterface $entityManager, CourRepository $courRepository): Response
    {

        $programmes = $courRepository->findProgramByCourId($cour->getId());

        foreach ($programmes as $programme) {
            $cour->removeProgramme($programme);
        }

        $entityManager->remove($cour);
        $entityManager->flush();

        return $this->redirectToRoute('app_cour');
    }

    /**
     * The function "show" in PHP renders a Twig template for displaying a specific "cour" object.
     * 
     * @param cour cour The parameter "cour" is a type-hinted variable that represents an instance of
     * the "cour" class. It is used to fetch a specific "cour" object from the database based on the
     * provided "id" parameter in the route. This object is then passed to the "show.html.twig
     * 
     * @return Response a Response object.
     */
    #[Route('/cour/{id}', name: 'show_cour')]
    public function show(cour $cour): Response
    {

        return $this->render('cour/show.html.twig', [
            'cour' => $cour
        ]);
    }
}
