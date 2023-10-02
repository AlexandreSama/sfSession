<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * Undocumented variable
     *
     * @var FormationRepository;
     */
    private $formationRepository;
    /**
     * Undocumented variable
     *
     * @var StagiaireRepository;
     */
    private $stagiaireRepository;

    public function __construct(FormationRepository $formationRepository, StagiaireRepository $stagiaireRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->stagiaireRepository = $stagiaireRepository;
    }

    /**
     * The function retrieves all sessions from the session repository and renders them in the session
     * index template.
     * 
     * @param SessionRepository sessionRepository An instance of the SessionRepository class, which is
     * responsible for retrieving session data from the database.
     * 
     * @return Response a Response object.
     */
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
            'sessions' => $sessions
        ]);
    }

    /**
     * This function is used to edit a session by handling the form submission, updating the session
     * data, and redirecting to the home page.
     * 
     * @param Session session The "session" parameter is an instance of the Session class. It is used
     * to retrieve the session object that needs to be edited.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em The "em" parameter is an instance of the EntityManagerInterface
     * class, which is responsible for managing the persistence of objects in the database. It is used
     * to persist the changes made to the Session object and flush them to the database.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function edit_session(Session $session, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(SessionType::class, $session);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $session = $form->getData();

            $em->persist($session);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('session/new.html.twig', [
            'controller_name' => 'SessionController',
            'formCreate' => $form,
            'edit' => $session->getId()
        ]);
    }

    /**
     * The function "new_session" creates a new session object, associates it with a formation object,
     * and saves it to the database.
     * 
     * @param Session session The  parameter is an instance of the Session entity class. It is
     * used to hold the data of the session being created or edited.
     * @param Formation formation The "formation" parameter is an instance of the Formation entity
     * class. It is used to associate the session with a specific formation. The value of this
     * parameter is retrieved from the request using the "idformation" parameter in the route.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, query parameters, and request body.
     * @param EntityManagerInterface em EntityManagerInterface object used for persisting and flushing
     * data to the database.
     * 
     * @return Response The code is returning a Response object.
     */
    #[Route('/session/new/{idformation}', name: 'new_session')]
    public function new_session(Session $session = null, Formation $formation = null, Request $request, EntityManagerInterface $em): Response{

        if(!$session){
            $session = new Session();
        }

        $formation = $this->formationRepository->find($request->get('idformation'));

        $form = $this->createForm(SessionType::class, $session);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $session = $form->getData();

            if($formation){
                $session->setFormation($formation);
            }

            $em->persist($session);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('session/new.html.twig', [
            'controller_name' => 'SessionController',
            'formCreate' => $form
        ]);

    }

    /**
     * The function "add_inscrit" adds a stagiaire (trainee) to a session and updates the database
     * accordingly.
     * 
     * @param Session session The "session" parameter is an instance of the Session entity class. It
     * represents a session object that is being added an inscription for.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em EntityManagerInterface object, used for managing entities in
     * the database.
     * 
     * @return Response a rendered view of the 'session/index.html.twig' template.
     */
    #[Route('/session/{id}/addinscrit/{idstagiaire}', name: 'add_inscrit_session')]
    public function add_inscrit(Session $session, Request $request, EntityManagerInterface $em): Response
    {

        $stagiaire = $this->stagiaireRepository->find($request->get('idstagiaire'));
        $session->addInscrit($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    /**
     * The function removes a specified student from a session and updates the session in the database.
     * 
     * @param Session session The "session" parameter is an instance of the Session class. It is used
     * to retrieve the session object from the database.
     * @param Request request The `` parameter is an instance of the `Request` class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em EntityManagerInterface object, used for managing entities in
     * the database.
     * 
     * @return Response a rendered view of the 'session/index.html.twig' template.
     */
    #[Route('/session/{id}/removeinscrit/{idstagiaire}', name: 'remove_inscrit_session')]
    public function remove_inscrit(Session $session, Request $request, EntityManagerInterface $em): Response
    {

        $stagiaire = $this->stagiaireRepository->find($request->get('idstagiaire'));
        $session->removeInscrit($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }

    /**
     * The function "show_session" renders the "index.html.twig" template with the controller name as a
     * variable.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}', name: 'show_session')]
    public function show_session(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
}
