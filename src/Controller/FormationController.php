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
        $formations = $formationRepository->findBy([], ['nomFormation' => 'ASC']);

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/formation/new', name: 'new_formation')]
    public function new(Formation $formation = null, Request $Request, EntityManagerInterface $entityManager ): Response
    {   
        // Create a new formation object
        $formation = new Formation();

        // Create form by formationType to add new formation 
        $form = $this->createForm(FormationType::class, $formation);
        // manage form in relation with de enter request 
        $form->handleRequest($Request);

        // If form is successful submit and valid 
        if ($form->isSubmitted() && $form->isValid()){

            // get form data and put it into $formation
            $formation = $form->getData();
            //prepare 
            $entityManager->persist($formation);
            // execute 
            $entityManager->flush();
            // redirect to formation list page
            return $this->redirectToRoute('app_formation');
        }
        
        return $this->render('formation/new.html.twig', [
            'formAddformation' => $form
        ]);
    }

    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    public function edit(Formation $formation, EntityManagerInterface $entityManager, Request $request) : Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $formation = $form->getData();

            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/new.html.twig', [
            'formCreate' => $form,
            'edit' => true
        ]);
    }

    #[Route('/formation/{id}/delete', name: 'delete_formation')]
    public function delete(Formation $formation, EntityManagerInterface $entityManager) : Response 
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('app_formation');
    }

    #[Route('/formation/{id}', name: 'show_formation')]
    public function show(Formation $formation) : Response
    {   

        return $this->render('formation/show.html.twig', [
            'formation' => $formation
        ]);
    }
}
