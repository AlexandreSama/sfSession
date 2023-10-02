<?php

namespace App\Controller;

use App\Entity\Cour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourController extends AbstractController
{
    #[Route('/cour', name: 'app_cour')]
    public function index(): Response
    {
        return $this->render('cour/index.html.twig', [
            'controller_name' => 'CourController',
        ]);
    }

    #[Route('/cour/new', name: 'new_cour')]
    public function new_cour(Cour $cour = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$cour){
            $cour = new Cour();
        }

        $form = $this->createForm(CategoryType::class, $cour);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $cour = $form->getData();

            $em->persist($cour);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('cour/new.html.twig', [
            'controller_name' => 'CourController',
            'formCreate' => $form
        ]);
    }

    /**
     * The function "edit_category" is a PHP function that handles the editing of a category entity and
     * updates it in the database.
     * 
     * @param Categorie categorie The "categorie" parameter is an instance of the "Categorie" class. It
     * is used to retrieve the category object from the database based on the "id" parameter in the
     * route.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em EntityManagerInterface object used for persisting and flushing
     * the changes made to the Categorie entity.
     * 
     * @return Response a Response object.
     */
    #[Route('/cour/{id}/edit', name: 'edit_cour')]
    public function edit_category(Cour $cour, Request $request, EntityManagerInterface $em): Response{

        $form = $this->createForm(CategoryType::class, $cour);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $cour = $form->getData();

            $em->persist($cour);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('cour/new.html.twig', [
            'controller_name' => 'CourController',
            'formCreate' => $form,
            'edit' => $cour->getId()
        ]);
    }
}
