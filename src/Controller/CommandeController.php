<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;

use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Knp\Component\Pager\PaginatorInterface;





class CommandeController extends AbstractController
{
 

    #[Route('/calendrier/comm', name: 'calendrier_commande', methods: ['GET'])]
    public function calendrier(CommandeRepository $commandeRepository): Response
    {
        // Retrieve all Commande entities
        $commandes = $commandeRepository->findAll();

        // Prepare an array to store calendar events
        $events = [];

        // Iterate over each Commande entity to create calendar events
        foreach ($commandes as $commande) {
            // Create an event for each Commande
            $event = [
                'title' => 'Commande ID :' . $commande->getId() ,
                'start' => $commande->getDatecmd()->format('Y-m-d'), // Use the date of the Commande as the event start date
                'url' => $this->generateUrl('app_commande_edit', ['id' => $commande->getId()]), // Link to the Commande details page
                // Add more event properties as needed
            ];

            // Add the event to the array
            $events[] = $event;
        }

        // Encode the events array to JSON for passing to the Twig template
        $data = json_encode($events);

        // Render the Twig template with the events data
        return $this->render('commande/calendrier.html.twig', compact('data'));
    }



    #[Route('/commade/pdf', name: 'pdfDhia', methods: ['GET'])]
    public function index_pdf(CommandeRepository $commandeRepository, Request $request): Response
    {
        // Création d'une nouvelle instance de la classe Dompdf
        $dompdf = new Dompdf();

        // Récupération des top 3 commandes par total price
        $commades = $commandeRepository->findAll();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/Front/img/1.png';
        // Encode the image to base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        // Génération du HTML à partir du template Twig 'commande/pdf_file.html.twig' en passant les top 3 commandes
        $html = $this->renderView('commande/pdf_file.html.twig', [
            'commades' => $commades,
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
            'Content-Disposition' => 'inline; filename="list.pdf"',
        ]);

        return $response;
    }

     #[Route('/commande/{id}', name: 'app_commande_new', methods: ['GET', 'POST'])]
     public function new($id, Request $request, EntityManagerInterface $entityManager, ProduitRepository $prodreop, UrlGeneratorInterface $urlGenerator,Security $security): Response
     {
        if(!$this->getUser()){
            throw $this->createAccessDeniedException("Vous n'avez pas accès à cette page");

        }

        // $user= new User();
        $user = $security->getUser();
         // Retrieve the product using the provided ID
         $produit = $prodreop->find($id);
         $commande = new Commande();
         $commande->setProduit($produit); // Set the product in the commande entity
     
         // Create the form, passing the commande entity with the associated product
         $form = $this->createForm(CommandeType::class, $commande, [
             'action' => $urlGenerator->generate('app_commande_new', ['id' => $id]), // Include the ID in the form action URL
             'method' => 'POST',
         ]);
         $commandeForm = $this->createForm(CommandeType::class, $commande);
    $commandeForm->get('productName')->setData($produit->getNomp());
     
         $form->handleRequest($request);
     
         if ($form->isSubmitted() && $form->isValid()) {
            $commande->setUser($user);
             $commande->setMontant($produit->getPrix() * $commande->getQuantite());
     
             $entityManager->persist($commande);
             $entityManager->flush();
     
             return $this->redirectToRoute('app_commande_new', ['id' => $id]); // Redirect to the same page with the ID
         }
     
         return $this->renderForm('front/commande.html.twig', [
             'commande' => $commande,
             'form' => $form,
             'selectedProduit' => $produit // Pass the selected product to the template

         ]);
     }
    
    


  

    #[Route('commandes/show', name: 'app_commande_show', methods: ['GET'])]
    public function show(CommandeRepository $CommandeRepository,PaginatorInterface $paginator,Request $request): Response
    {
        
        
        $queryBuilder = $CommandeRepository->createQueryBuilder('r')
        ->orderBy('r.id', 'DESC');

        $Commandeliste = $paginator->paginate(
            $queryBuilder->getQuery(), // Pass the query to paginate
            $request->query->getInt('page', 1), // Current page number, default is 1
            1 // Number of items per page
        );   


        return $this->render('commande/show.html.twig', [
            'commande' => $Commandeliste,
        ]);
    }

    #[Route('commande/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $produit= $commande->getProduit();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setMontant($produit->getPrix() * $commande->getQuantite());

            $entityManager->flush();

            return $this->redirectToRoute('app_commande_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_show', [], Response::HTTP_SEE_OTHER);
    }



     // public function new($id,Request $request, EntityManagerInterface $entityManager,ProduitRepository $prodreop ): Response
    // {
    //     $commande = new Commande();
    //     $produit= $prodreop->find($id);
    //     $form = $this->createForm(CommandeType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commande->setMontant( $produit->getPrix()*$commande->getQuantite());
    //         $commande->setProduit($produit);

    //         $entityManager->persist($commande);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commande_new', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('front/commande.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }
}
