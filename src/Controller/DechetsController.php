<?php

namespace App\Controller;

use App\Entity\Dechets;
use App\Form\DechetsType;
use App\Repository\DechetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/dechets')]
class DechetsController extends AbstractController
{
    #[Route('/', name: 'app_dechets_index', methods: ['GET'])]
    public function index(DechetsRepository $dechetsRepository,
    PaginatorInterface $paginator,Request $request): Response
    {

        $productData = $dechetsRepository->countByType();
        
        $queryBuilder = $dechetsRepository->createQueryBuilder('r')
        ->orderBy('r.id', 'ASC');

    // Get the paginated results
        $dechets = $paginator->paginate(
        $queryBuilder->getQuery(), // Pass the query to paginate
        $request->query->getInt('page', 1), // Current page number, default is 1
        1 // Number of items per page
    );

        
        return $this->render('dechets/index.html.twig', [
            'dechets' => $dechets,
            'productData' => $productData,
        ]);
    }


    #[Route('/b', name: 'app_dechets_CR', methods: ['GET'])]
    public function indexcroissant(DechetsRepository $dechetsRepository,
    PaginatorInterface $paginator,Request $request): Response
    {

        $productData = $dechetsRepository->countByType();
        $queryBuilder = $dechetsRepository->createQueryBuilder('r')
        ->orderBy('r.quantite', 'DESC');
    // Get the paginated results
        $dechets = $paginator->paginate(
        $queryBuilder->getQuery(), // Pass the query to paginate
        $request->query->getInt('page', 1), // Current page number, default is 1
        5 // Number of items per page
    );

        
        return $this->render('dechets/index.html.twig', [
            'dechets' => $dechets,
            'productData' => $productData,
        ]);
    }


    #[Route('/a', name: 'app_dechets_DR', methods: ['GET'])]
    public function indexdecroissant(DechetsRepository $dechetsRepository,
    PaginatorInterface $paginator,Request $request): Response
    {

        $productData = $dechetsRepository->countByType();
        
        $queryBuilder = $dechetsRepository->createQueryBuilder('r')
        ->orderBy('r.quantite', 'ASC');

    // Get the paginated results
        $dechets = $paginator->paginate(
        $queryBuilder->getQuery(), // Pass the query to paginate
        $request->query->getInt('page', 1), // Current page number, default is 1
        5 // Number of items per page
    );

        
        return $this->render('dechets/index.html.twig', [
            'dechets' => $dechets,
            'productData' => $productData,
        ]);
    }

    #[Route('/back/new', name: 'app_dechets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $dechet = new Dechets();
        $form = $this->createForm(DechetsType::class, $dechet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $brochureFile = $form->get('image')->getData();

       // Process the uploaded file if it exists
       if ($brochureFile instanceof UploadedFile) {
           $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
           // Sanitize the filename to ensure safe URL usage
           $safeFilename = $slugger->slug($originalFilename);
           // Generate a unique filename to prevent conflicts
           $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

           // Move the uploaded file to the desired directory
           try {
               $brochureFile->move(
                   $this->getParameter('dechets_directory'),
                   $newFilename
               );
           } catch (FileException $e) {
               // Handle any exceptions that occur during file upload
               // For example, you could log the error or show a flash message
               // and redirect the user back to the form
           }

           // Set the filename in your entity
           $dechet->setImage($newFilename);
       }
            $entityManager->persist($dechet);
            $entityManager->flush();

            return $this->redirectToRoute('app_dechets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/Adechet.html.twig', [
            'dechet' => $dechet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dechets_show', methods: ['GET'])]
    public function show(Dechets $dechet): Response
    {
        return $this->render('dechets/show.html.twig', [
            'dechet' => $dechet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dechets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dechets $dechet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DechetsType::class, $dechet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dechets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dechets/edit.html.twig', [
            'dechet' => $dechet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dechets_delete', methods: ['POST'])]
    public function delete(Request $request, Dechets $dechet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dechet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dechet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dechets_index', [], Response::HTTP_SEE_OTHER);
    }

    /*#[Route('/products', name: 'product_list')]
    public function find(DechetsRepository $productRepository): Response
    {
        $dechet = $productRepository->findAll();

        // Now you can pass $products to a template, or return a JSON response, etc.
        return $this->render('front/list_dechet.html.twig', [
            'dechet' => $dechet,
        ]);
    }*/


    #[Route('/reserch', name: 'app_reserch')]
    public function reserch(Request $request, DechetsRepository $dechetsRepository): Response
    {
        $searchQuery = $request->query->get('q');
        if ($request->query->has('clear')) {
            // Redirect to the same route without the 'q' parameter
            return $this->redirectToRoute('app_produit');
        }

        if ($searchQuery) {
            $dechet = $dechetsRepository->findBySearchQuery($searchQuery);
        } else {
            $dechet = $dechetsRepository->findAll();
        }
       

        return $this->render('dechets/index.html.twig', [
            'dechet' => $dechet,
            'searchQuery' => $searchQuery,
        ]);
    }


}
