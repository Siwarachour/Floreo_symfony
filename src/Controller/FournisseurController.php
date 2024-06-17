<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurController extends AbstractController
{
    #[Route('/fournisseur', name: 'app_fournisseur')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupérer tous les utilisateurs depuis le repository
        $users = $userRepository->findAll();

        // Filtrer les utilisateurs ayant le rôle 'ROLE_FOURNISSEUR'
        $fournisseurs = array_filter($users, function ($user) {
            return in_array('ROLE_FOURNISSEUR', $user->getRoles());
        });

        // Afficher les fournisseurs dans le terminal pour débogage
        dump($fournisseurs);

        // Rendre la vue 'user/fournisseur.html.twig' en passant la liste des fournisseurs
        return $this->render('user/fournisseur.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
}
