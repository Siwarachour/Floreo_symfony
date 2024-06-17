<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier as FlashyBundleFlashyNotifier;
use Dompdf\Dompdf;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{

    #[Route('/pdf', name: 'pdfwess', methods: ['GET'])]
    public function index_pdf(ReclamationRepository $reclamationRepository, Request $request): Response
    {
        // Création d'une nouvelle instance de la classe Dompdf
        $dompdf = new Dompdf();

        // Récupération des top 3 commandes par total price
        $reclamations = $reclamationRepository->findAll();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/1.jpg';
        // Encode the image to base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        // Génération du HTML à partir du template Twig 'commande/pdf_file.html.twig' en passant les top 3 commandes
        $html = $this->renderView('reclamation/pdf_file.html.twig', [
            'top3Commandes' => $reclamations,
            'imagePath' => $imageSrc,
        ]);

        // Récupération des options de Dompdf et activation du chargement des ressources à distance
        $options = $dompdf->getOptions();
        $options->set([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,  // Enable PHP rendering
        ]);

        $dompdf->setOptions($options);

        // Chargement du HTML généré dans Dompdf
        $dompdf->loadHtml($html);

        // Configuration du format de la page en A4 en mode portrait
        $dompdf->setPaper('A4', 'portrait');

        // Génération du PDF
        $dompdf->render();

        // Récupération du contenu du PDF généré
        $output = $dompdf->output();

        // Set headers for PDF download
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reclamation.pdf"',
        ]);

        return $response;
    }
    #[Route('/back', name: 'app_reclamation_indexback', methods: ['GET'])]
    public function indexback(
        ReclamationRepository $reclamationRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Build the query to fetch reclamations
        $queryBuilder = $reclamationRepository->createQueryBuilder('r')
            ->orderBy('r.dateajout', 'DESC');
    
        // Get the paginated results
        $reclamations = $paginator->paginate(
            $queryBuilder->getQuery(), // Pass the query to paginate
            $request->query->getInt('page', 1), // Current page number, default is 1
            5 // Number of items per page
        );
    
        // Render the view with the paginated reclamations
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
      
    
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository,EntityManagerInterface $entityManager): Response
    {
          // Check if the user is authenticated
          if (!$this->getUser()) {
            throw new AccessDeniedException('You must be logged in to view this page.');
        }

        $userId = $this->getUser()->getId();

        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findByUserId($userId);

        return $this->render('reclamation/indexfront.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyBundleFlashyNotifier $flashy): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
           

            $entityManager->flush();
            $flashy->success('Article added!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation,EntityManagerInterface $entityManager): Response
    {
        $reclamationId = $reclamation->getId();
        $reponses = $entityManager
        ->getRepository(Reponse::class)
        ->findResponsesByReclamationId($reclamationId);
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
        ]);
    }
    #[Route('/reclamation/{id}/back', name: 'app_reclamation_showback', methods: ['GET'])]
    public function showback(Reclamation $reclamation ,EntityManagerInterface $entityManager): Response
    {
        // Retrieve associated Reponses by Reclamation id
        $reclamationId = $reclamation->getId();
        $reponses = $entityManager
                ->getRepository(Reponse::class)
                ->findResponsesByReclamationId($reclamationId);

        return $this->render('reclamation/showback.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }


  

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete/back', name: 'app_reclamation_deleteback', methods: ['POST'])]
    public function deleteback(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_indexback', [], Response::HTTP_SEE_OTHER);
    }
}
