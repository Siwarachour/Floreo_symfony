<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\Evenement1Type;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Controller\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/afficheback', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

//afficher all events in front


    #[Route('/frontev', name: 'app_evenement_dispfront', methods: ['GET'])]
    public function dispfront(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
           // yejbd m bd les events existant
    $allEvents= $evenementRepository->findAll();

    // Paginate the results yekho variable events o ihot fyh les events ljbdenhom o yaamlhom pagination
    $events = $paginator->paginate(
        $allEvents, // Query to paginate
        $request->query->getInt('page', 1), // Current page number, default is 1
        6 // Number of items per page max 3 events f kol page
    );
        return $this->render('frontevent/eventafficher.html.twig', [
            'evenements' => $events, //naamlo app l evenements baaed f template tanaa
        ]);
    }


    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(Evenement1Type::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
       // Get the uploaded file from the form
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
                   $this->getParameter('Evenement_directory'),
                   $newFilename
               );
           } catch (FileException $e) {
               // Handle any exceptions that occur during file upload
               // For example, you could log the error or show a flash message
               // and redirect the user back to the form
           }

           // Set the filename in your entity
           $evenement->setImage($newFilename);
       }

            
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }


    //afficher detail in front
    #[Route('/detailf{id}', name: 'detailf', methods: ['GET'])]
    public function showdetailFr(Evenement $evenement): Response
    {
        return $this->render('frontevent/eventdetail.html.twig', [
            'evenement' => $evenement,
        ]);
    }

//affiche detail back

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(Evenement1Type::class, $evenement);
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
                        $this->getParameter('Evenement_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle any exceptions that occur during file upload
                    // For example, you could log the error or show a flash message
                    // and redirect the user back to the form
                }
     
                // Set the filename in your entity
                $evenement->setImage($newFilename);
            }
     
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/ev/calendar', name: 'calendar')]
 
    public function calendar(EvenementRepository $evenementRepository): Response
    { 
        // $event = $calendar->findAll();
        $event = $evenementRepository->findAll();
        $rdvs = [];
        $allDay = true;
        foreach ($event as $event) {
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDatedebut()->format('Y-m-d H:i:s'),
                'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
                'title' => $event->getNameevent(),
                'description' => $event->getDescription(),
                'backgroundColor' => "#0000ff",
                'borderColor' => "#ff0000",
                'textColor' => "#ffffff",
                'allDay' => $allDay,
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('frontevent/calnd1.html.twig', compact('data'));
        /*  return $this->render('base_back/voyage/calendar.html.twig', [
                'controller_name' => 'VoyageController',
            ]);
        */
    }
        
    #[Route('/participate/{id}', name: 'app_evenement_participate', methods: ['POST'])]
    public function participate(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            throw new AccessDeniedHttpException('You must be logged in to participate.');
        }
    
        // Check if the user is already participating
        if ($evenement->getUser()->contains($user)) {
            return new JsonResponse(['message' => 'You are already participating.'], Response::HTTP_BAD_REQUEST);
        }
    
        // Check if the participation count is greater than 0
        if ($evenement->getNbparticipant() <= 0) {
            return new JsonResponse(['message' => 'No more available spots for participation.'], Response::HTTP_BAD_REQUEST);
        }
    
        // Decrease the participation count
        $evenement->setNbparticipant($evenement->getNbparticipant() - 1);
    
        // Add the user to the event participants
        $evenement->addUser($user);
    
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Participation successful.'], Response::HTTP_OK);
    }
    
}
