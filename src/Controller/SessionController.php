<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionEditType;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * This PHP function retrieves sessions from a session repository and renders them in a Twig
     * template.
     * 
     * @param SessionRepository sessionRepository The sessionRepository is an instance of the
     * SessionRepository class, which is responsible for retrieving session data from the database. It
     * is injected into the index method using dependency injection.
     * 
     * @return Response a Response object.
     */
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {   
        $sessios = $sessionRepository->findBy([], ['dateDebut' => 'ASC']);

        return $this->render('session/index.html.twig', [
            'sessions' => $sessios
        ]);
    }

    /**
     * This PHP function creates a new session and saves it to the database if the form is submitted
     * and valid.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request, such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects to
     * the database. It provides methods for persisting, updating, and deleting entities, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{idformation}/new', name: 'new_session')]
    public function new($idformation, FormationRepository $formationRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();

        $form = $this->createForm(SessionType::class,$session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            
            $formation = $formationRepository->findOneBy(['id' => $idformation]);
            $session->setFormation($formation);

            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig',[
            'formCreate' => $form,
            'sessionId' => $session->getId(),
            'edit' => false
        ]);

    }

    /**
     * The function "edit" is used to handle the editing of a session entity in a PHP application,
     * including form submission and database persistence.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and query parameters.
     * @param Session session The `` parameter is an instance of the `Session` entity class. It
     * represents the session object that is being edited.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for persisting, updating, and deleting entities, as well as
     * querying the database. In this code, the `` is used to
     * 
     * @return Response a Response object.
     */
    #[Route('/session/edit/{id}', name: 'edit_session')]
    public function edit(Request $request, Session $session, EntityManagerInterface $entityManager) : Response
    {

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();

            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig',[
            'formCreate' => $form,
            'sessionId' => $session->getId(),
            'edit' => true
        ]);
    }



    /**
     * This PHP function deletes a session and removes its associations with related entities.
     * 
     * @param Session session The "session" parameter is an instance of the Session class. It
     * represents a specific session object that needs to be deleted.
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of objects in
     * the database. It provides methods for querying, inserting, updating, and deleting entities.
     * @param StagiaireRepository stagiaireRepository The `` is an instance of the
     * `StagiaireRepository` class, which is responsible for retrieving and managing `Stagiaire`
     * entities from the database. It provides methods such as `findAll()`, which returns an array of
     * all `Stagiaire`
     * @param FormationRepository formationRepository The formationRepository is an instance of the
     * FormationRepository class, which is responsible for retrieving and manipulating data related to
     * formations from the database. It is used in the delete method to retrieve all formations
     * associated with the session being deleted.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager, StagiaireRepository $stagiaireRepository, FormationRepository $formationRepository) : Response
    {   
        $entityManager->remove($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_session');
    }

    /**
     * This PHP function adds a stagiaire (trainee) to a session and updates the number of available
     * places in the session.
     * 
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of entities in
     * the database. It provides methods for querying, persisting, and flushing entities.
     * @param Session session The "session" parameter is an instance of the Session entity class. It
     * represents a specific session object.
     * @param int id The "id" parameter in the route refers to the ID of the session. It is used to
     * identify the session to which the stagiaire (trainee) will be added.
     * @param int idstagiaire The parameter "idstagiaire" represents the ID of the stagiaire (trainee)
     * that you want to add to the session.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}/add/{idstagiaire}', name: 'add_session')]
    public function stagiaireToSession(EntityManagerInterface $entityManager, Session $session, int $id, int $idstagiaire): Response
    {

        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($idstagiaire);

        if($session->getNbPlace() > count($session->getInscrit())){
            $stagiaire->addSession($session);

            $entityManager->persist($stagiaire);
    
            $entityManager->flush();

            return $this->redirectToRoute('show_session', ['id' => $id]);
        }else{
            $this->addFlash('error', 'Pas assez de place dans la session');
            return $this->redirectToRoute('show_session', ['id' => $id]);
        }

    }

    /**
     * This PHP function removes a stagiaire (trainee) from a session and updates the session's
     * available places.
     * 
     * @param EntityManagerInterface entityManager The `` parameter is an instance of the
     * `EntityManagerInterface` class, which is responsible for managing the persistence of entities in
     * the database. It provides methods for querying, persisting, and flushing entities.
     * @param Session session The "session" parameter is an instance of the Session entity class. It
     * represents a session object in the application.
     * @param id The "id" parameter represents the ID of the session that the stagiaire (trainee) is
     * being removed from.
     * @param idstagiaire The parameter "idstagiaire" represents the ID of the stagiaire (trainee) that
     * needs to be removed from the session.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}/remove/{idstagiaire}', name: 'remove_session')]
    public function removeStagiaireToSession(EntityManagerInterface $entityManager, Session $session, $id, $idstagiaire): Response
    {
        $stagiaire = $entityManager->getRepository(Stagiaire::class)->find($idstagiaire);

        $session->setNbPlace($session->getNbPlace() + 1);
        $stagiaire->removeSession($session);

        $entityManager->persist($stagiaire);

        $entityManager->flush();

        return $this->redirectToRoute('show_session', ['id' => $id]); // Redirige vers le détail de la session
    }

    /**
     * This PHP function generates and streams a PDF attestation of completion for a training session
     * and a specific trainee.
     * 
     * @param Session session The "session" parameter is an instance of the "Session" entity class. It
     * represents a specific session of a training program.
     * @param stagiaireid The `stagiaireid` parameter is the ID of the stagiaire (trainee) for whom the
     * attestation (certificate) is being generated.
     * @param StagiaireRepository stagiaireRepository The `` parameter is an
     * instance of the `StagiaireRepository` class, which is responsible for retrieving and
     * manipulating data related to the `Stagiaire` entity from the database. It is used in the
     * `attestation_fin_formation` method to fetch a
     * 
     * @return Response a Response object with an empty body, a status code of 200, and a Content-Type
     * header of 'application/pdf'.
     */
    #[Route('/session/{id}/attestation/{stagiaireid}', name:'attestation_formation')]
    public function attestation_fin_formation(Session $session, $stagiaireid, StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaire = $stagiaireRepository->findOneBy(['id' => $stagiaireid]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->renderView('formation/attestation.html.twig',
        ['stagiaire' => $stagiaire, 'session' => $session]));
        $dompdf->render();
        $dompdf->stream('attestation_' . $stagiaire->getNom() . '.pdf', array('Attachment' => 0));

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * This PHP function retrieves a session and displays it along with a list of stagiaires (trainees)
     * who are not already in the session.
     * 
     * @param Session session The "session" parameter is an instance of the Session class. It is used
     * to represent a specific session object.
     * @param SessionRepository sessionRepository The sessionRepository parameter is an instance of the
     * SessionRepository class. It is used to retrieve data from the database related to sessions. In
     * this case, it is used to fetch a list of stagiaires (trainees) who are not already in the
     * specified session.
     * 
     * @return Response a Response object.
     */
    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository) : Response
    {
        // dd($session);
        //Seconde ou l'on récupère uniquement ceux qui ne sont pas dans la session spécifique
        $stagiairesNotInSession = $sessionRepository->findByStagiairesNotInSession($session->getId());
        $date = new \DateTime();

        $interval = $date->diff($session->getDateFin());

        if($interval->invert){
            $finish = true;
        }else{
            $finish = false;
        }

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiairesNotInSession,
            'finish' => $finish
        ]);
    }
}
