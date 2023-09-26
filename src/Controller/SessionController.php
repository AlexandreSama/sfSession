<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
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

    public function __construct(FormationRepository $formationRepository)
    {
        $this->formationRepository = $formationRepository;
    }

    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
            'sessions' => $sessions
        ]);
    }

    #[Route('/session/new/{idformation}', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_session(Session $session = null, Formation $formation = null, Request $request, EntityManagerInterface $em): Response
    {

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
            'formCreate' => $form,
            'edit' => $session->getId()
        ]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show_session(): Response
    {
        return $this->render('session/index.html.twig', [
            'controller_name' => 'SessionController',
        ]);
    }
}
