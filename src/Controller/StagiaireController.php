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
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {   
        $stagiaires = $stagiaireRepository->findBy([], ['lastname' => 'ASC']);

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

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
            'formAddstagiaire' => $form->createView()
        ]);
    }


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

        return $this->render('stagiaire/edit.html.twig',[
            'formEditstagiaire' => $form
        ]);
    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(stagiaire $stagiaire, EntityManagerInterface $entityManager) : Response
    {   
        $entityManager->remove($stagiaire);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_stagiaire');
    }

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function show(stagiaire $stagiaire) : Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
}
