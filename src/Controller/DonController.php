<?php

namespace App\Controller;

use App\Entity\Don;
use App\Form\DonType;
use App\Repository\DonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;



#[Route('/don')]
class DonController extends AbstractController
{




    #[Route('/indexx', name: 'app_don_index', methods: ['GET'])]
    public function index(DonRepository $donRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $perPage = 6;
        $currentPage = $request->query->getInt('page', 1);

        // Créer une requête pour récupérer les dons
        $query = $entityManager->createQueryBuilder()
            ->select('d')
            ->from('App\Entity\Don', 'd')
            ->getQuery();

        // Paginer les résultats
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($currentPage - 1))
            ->setMaxResults($perPage);

        // Récupérer les dons pour la page actuelle
        $dons = $paginator->getQuery()->getResult();

        // Calculer le nombre total de pages
        $totalPages = ceil(count($paginator) / $perPage);

        // Récupérer les statistiques de dons
        $donStatistics = $entityManager->createQueryBuilder()
            ->select('d.type, COUNT(d.id) as donCount')
            ->from('App\Entity\Don', 'd')
            ->groupBy('d.type')
            ->getQuery()
            ->getResult();

        return $this->render('don/index.html.twig', [
            'dons' => $dons,
            'donStatistics' =>  $donStatistics,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
        ]);
    }






    #[Route('/calendrier', name: 'calendrier_don', methods: ['GET'])]
    public function calendrier(DonRepository $donRepository): Response
    {
        // Retrieve all Commande entities
        $dons = $donRepository->findAll();

        // Prepare an array to store calendar events
        $events = [];

        // Iterate over each Commande entity to create calendar events
        foreach ($dons as $don) {
            // Create an event for each Commande
            $event = [
                'title' => 'donation' . $don->getType(), 
                'start' => $don->getDateDon()->format('Y-m-d'), // Use the date of the Commande as the event start date
                'url' => $this->generateUrl('app_don_show', ['id' => $don->getId()]), // Link to the Commande details page
                // Add more event properties as needed
            ];

            // Add the event to the array
            $events[] = $event;
        }

        // Encode the events array to JSON for passing to the Twig template
        $data = json_encode($events);

        // Render the Twig template with the events data
        return $this->render('donback/calendrier.html.twig', compact('data'));
    }

    #[Route('/new', name: 'app_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Security $security): Response
    {
        if(!$this->getUser()){
            throw $this->createAccessDeniedException("Vous n'avez pas accès à cette page");

        }

        $user = $security->getUser();
        $don = new Don();
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $don->setUser($user);
            $entityManager->persist($don);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_don_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('don/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonType::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('don/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_don_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$don->getId(), $request->request->get('_token'))) {
            $entityManager->remove($don);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
