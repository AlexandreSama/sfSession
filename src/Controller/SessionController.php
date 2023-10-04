<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {   
        $sessios = $sessionRepository->findBy([], ['dateDebut' => 'ASC']);

        return $this->render('session/index.html.twig', [
            'sessions' => $sessios
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Request $request, Session $session = null, EntityManagerInterface $entityManager) : Response
    {
        if(!$session){
            $session = new Session(); 
        }
        $form = $this->createForm(SessionType::class,$session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();

            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig',[
            'form' => $form,
            'sessionId' => $session->getId()
        ]);
    }

    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityManager) : Response
    {   
        $entityManager->remove($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_session');
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session) : Response
    {
        return $this->render('session/show.html.twig', [
            'session' => $session,
        ]);
    }
}
