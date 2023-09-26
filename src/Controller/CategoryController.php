<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/new', name: 'new_category')]
    public function index(Categorie $categorie = null, Request $request, EntityManagerInterface $em): Response
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
            'controller_name' => 'FormationController',
            'formCreate' => $form,
            'edit' => $categorie->getId()
        ]);
    }
}
