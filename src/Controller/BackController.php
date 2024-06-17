<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    #[Route('/back', name: 'app_back')]
    // Ajoutez l'annotation IsGranted pour restreindre l'accès aux utilisateurs ayant le rôle ROLE_ADMIN
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('dashboard/base.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
}
