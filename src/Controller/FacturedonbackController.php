<?php

namespace App\Controller;

use App\Entity\FactureDon;
use App\Form\FactureDon1Type;
use App\Repository\FactureDonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/facturedonback')]
class FacturedonbackController extends AbstractController
{
    #[Route('/', name: 'app_facturedonback_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, FactureDonRepository $factureDonRepository): Response
    {
        // Récupérer toutes les factures non paginées
        $allFactureDonsQuery = $factureDonRepository->findAllQuery();

        // Paginer les résultats
        $factureDons = $paginator->paginate(
            $allFactureDonsQuery, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page par défaut
            10 // Nombre d'éléments par page
        );

        // Rendre la vue avec les résultats paginés
        return $this->render('facturedonback/index.html.twig', [
            'facture_dons' => $factureDons,
        ]);
    }

    #[Route('/new', name: 'app_facturedonback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factureDon = new FactureDon();
        $form = $this->createForm(FactureDon1Type::class, $factureDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($factureDon);
            $entityManager->flush();

            return $this->redirectToRoute('app_facturedonback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturedonback/new.html.twig', [
            'facture_don' => $factureDon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturedonback_show', methods: ['GET'])]
    public function show(FactureDon $factureDon): Response
    {
        return $this->render('facturedonback/show.html.twig', [
            'facture_don' => $factureDon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facturedonback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureDon $factureDon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureDon1Type::class, $factureDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facturedonback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facturedonback/edit.html.twig', [
            'facture_don' => $factureDon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facturedonback_delete', methods: ['POST'])]
    public function delete(Request $request, FactureDon $factureDon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureDon->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factureDon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facturedonback_index', [], Response::HTTP_SEE_OTHER);
    }




}
