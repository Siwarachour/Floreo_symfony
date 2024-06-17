<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs depuis le repository
        $users = $userRepository->findAll();

        // Filtrer les utilisateurs ayant le rôle 'ROLE_CLIENT'
        $clients = array_filter($users, function ($user) {
            return in_array('ROLE_CLIENT', $user->getRoles());
        });

        // Rendre la vue 'user/client.html.twig' en passant la liste des clients
        return $this->render('user/client.html.twig', [
            'clients' => $clients,
        ]);
    }
}
