<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsernewType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{#[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();
    
        // Récupérez le terme de recherche à partir de la requête
        $searchTerm = $request->query->get('term');
    
        // Si un terme de recherche est fourni, effectuez la recherche
        if ($searchTerm) {
            // Effectuez votre logique de recherche ici
            // Par exemple, utilisez la méthode de recherche de votre repository
            $searchResults = $this->getDoctrine()->getRepository(User::class)->search($searchTerm);
    
            // Retournez les résultats de la recherche au format JSON
            return new JsonResponse($searchResults);
        }
    
        // Si aucun terme de recherche n'est fourni, affichez la liste des utilisateurs normalement
        // Utiliser dump() pour afficher les données des utilisateurs dans le terminal
        dump($userRepository->findAll());
    
        // Passer les données des utilisateurs à la vue
        return $this->render('user/base.html.twig', [
            'user' => $user,
            'users' => $userRepository->findAll(),
        ]);
    }
    


    #[Route('/user/{id}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'disabled' => true, 
        ]);

        return $this->render('user/showuser.html.twig', [
            'user' => $user,
            'form' => $form->createView(), 
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/update/{id}', name: 'app_user_update')]
    public function update(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $entityManager->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/updateuser.html.twig', [
            'user' => $user, 
            'form' => $form->createView(),
        ]);

    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UsernewType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le téléchargement de l'image de l'utilisateur
            $imageFile = $form->get('image')->getData();
    
            if ($imageFile) {
                // Gérer le stockage de l'image, par exemple :
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
    
                // Associer le nom du fichier d'image à l'utilisateur
                $user->setImage($fileName);
            }
    
            // Encoder le mot de passe de l'utilisateur
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
    
            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Rediriger vers la liste des utilisateurs avec un message flash
            $this->addFlash('success', 'User successfully added!');
            return $this->redirectToRoute('app_user');
        }
    
        // Rendre le formulaire et la vue associée
        return $this->render('user/newuser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/banUser/{id}', name: 'ban_user', methods: ['GET', 'POST'])]
    public function banUser(Request $request, UserRepository $userRepository,int $id): Response
    {
        // Retrieve the search query from the request
        $user = $userRepository->find($id);
  
        // Perform the search operation based on the query
         $userRepository->banUnbanUser($user);
  
        // Return the search results as JSON response
        return $this->redirectToRoute('app_back');
    }

    #[Route("/stat", name: "stat")]
    public function statistique1(UserRepository $userRepository)
    {
        // Récupérer le nombre d'utilisateurs pour chaque rôle
        $rolesCount = $userRepository->countUsersByRole();
    
        // Récupérer le nombre d'utilisateurs pour chaque tranche d'âge
        $ageCounts = $userRepository->countByAge();
    
        // Initialiser des tableaux pour stocker les données
        $roles = [];
        $usersCount = [];
        $ageGroups = [];
        $userCounts = [];
    
        // Parcourir les résultats pour extraire les données sur les rôles
        foreach ($rolesCount as $roleCount) {
            $roles[] = $roleCount['role'];
            $usersCount[] = $roleCount['count'];
        }
    
        // Parcourir les résultats pour extraire les données sur les tranches d'âge
        foreach ($ageCounts as $age => $count) {
            $ageGroups[] = $age;
            $userCounts[] = $count;
        }
    
        // Passer les données à la vue
        return $this->render('user/statistique.html.twig', [
            'roles' => $roles,
            'usersCount' => $usersCount,
            'ageGroups' => $ageGroups,
            'userCounts' => $userCounts,
        ]);
    }
    
    
    

}