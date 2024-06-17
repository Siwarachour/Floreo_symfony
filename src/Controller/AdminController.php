<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        // Assurez-vous que l'utilisateur est authentifié
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        // Récupérez l'utilisateur actuel
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est authentifié et vérifié
        if ($user && $user->isVerified()) {
            // Vérifiez si l'utilisateur a le rôle de fournisseur
           
                return $this->redirectToRoute('app_login'); // Remplacez 'page_front' par le nom de la route de votre page front
            
        }
        return $this->redirectToRoute('app_login');
       
       }
}