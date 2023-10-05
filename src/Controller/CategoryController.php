<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategoryType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    #[Route('/category', name: 'app_category')]
    public function index(CategorieRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['nomCategorie' => 'ASC']);

        return $this->render('category/index.html.twig', [
            'Categories' => $categories,
        ]);
    }

    /**
     * This PHP function handles the creation of a new category, including form validation and database
     * persistence.
     * 
     * @param Categorie categorie The "categorie" parameter is an instance of the Categorie class. It
     * is used to hold the data of the category being created or edited.
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, headers, and query parameters.
     * @param EntityManagerInterface em EntityManagerInterface object used for persisting and flushing
     * data to the database.
     * 
     * @return Response a Response object.
     */
    #[Route('/category/new', name: 'new_category')]
    public function new_category(Categorie $categorie = null, Request $request, EntityManagerInterface $em): Response
    {
        if(!$categorie){
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $categorie = $form->getData();

            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/new.html.twig', [
            'controller_name' => 'CategoryController',
            'formCreate' => $form,
            'edit' => $categorie->getId()
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
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function edit_category(Categorie $categorie, Request $request, EntityManagerInterface $em): Response{

        $form = $this->createForm(CategoryType::class, $categorie);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $categorie = $form->getData();

            $em->persist($categorie);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('formation/new.html.twig', [
            'controller_name' => 'CategoryController',
            'formCreate' => $form,
            'edit' => $categorie->getId()
        ]);
    }
}
