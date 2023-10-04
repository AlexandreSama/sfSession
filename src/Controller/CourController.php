<?php

namespace App\Controller;

use App\Entity\Cour;
use App\Repository\CourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{

    #[Route('/cour', name: 'app_cour')]
    public function index(CourRepository $courRepository): Response
    {
        $cours = $courRepository->findBy([], ['name' => 'ASC']);

        return $this->render('cour/index.html.twig', [
            'cours' => $cours,
        ]);
    }

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
            'formAddcour' => $form
        ]);
    }

    #[Route('/cour/{id}/edit', name: 'edit_cour')]
    public function edit(cour $cour = null, Request $Request, EntityManagerInterface $entityManager): Response
    {
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

        return $this->render('/cour/edit.html.twig', [
            'formEditcour' => $form
        ]);
    }

    #[Route('/cour/{id}/delete', name: 'delete_cour')]
    public function delete(cour $cour, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($cour);
        $entityManager->flush();

        return $this->redirectToRoute('app_cour');
    }

    #[Route('/cour/{id}', name: 'show_cour')]
    public function show(cour $cour): Response
    {

        return $this->render('cour/show.html.twig', [
            'cour' => $cour
        ]);
    }
}
