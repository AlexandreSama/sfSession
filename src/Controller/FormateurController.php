<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormateurController extends AbstractController
{
    /**
     * The function "index" in the "FormateurController" class renders the "index.html.twig" template
     * with the controller name as a variable.
     * 
     * @return Response a Response object.
     */
    #[Route('/formateur/{id}', name: 'app_formateur')]
    public function index(): Response
    {
        return $this->render('formateur/index.html.twig', [
            'controller_name' => 'FormateurController',
        ]);
    }
}
