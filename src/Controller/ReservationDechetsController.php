<?php

    namespace App\Controller;

    use App\Entity\ReservationDechets;
    use App\Form\ReservationDechetsType;
    use App\Repository\ReservationDechetsRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Knp\Component\Pager\PaginatorInterface;

    #[Route('/reservation/dechets')]
    class ReservationDechetsController extends AbstractController
    {
        #[Route('/', name: 'app_reservation_dechets_index', methods: ['GET'])]
        public function index(ReservationDechetsRepository $reservationDechetsRepository,PaginatorInterface $paginator,Request $request): Response
        {
            
            
        
        
        $queryBuilder = $reservationDechetsRepository->createQueryBuilder('r')
        ->orderBy('r.id', 'DESC');

    // Get the paginated results
        $reservationDechets = $paginator->paginate(
        $queryBuilder->getQuery(), // Pass the query to paginate
        $request->query->getInt('page', 1), // Current page number, default is 1
        1 // Number of items per page
    );          



            return $this->render('front/list_reservation.html.twig', [
                'reservation_dechets' => $reservationDechets,
            
            ]);
        }

        #[Route('/new', name: 'app_reservation_dechets_new', methods: ['GET', 'POST'])]
        public function new(Request $request, EntityManagerInterface $entityManager): Response
        {
            $reservationDechet = new ReservationDechets();
            $form = $this->createForm(ReservationDechetsType::class, $reservationDechet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($reservationDechet);
                $entityManager->flush();

                return $this->redirectToRoute('app_reservation_dechets_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reservation_dechets/new.html.twig', [
                'reservation_dechet' => $reservationDechet,
                'form' => $form,
            ]);
        }

        #[Route('/{id}', name: 'app_reservation_dechets_show', methods: ['GET'])]
        public function show(ReservationDechets $reservationDechet): Response
        {
            return $this->render('reservation_dechets/show.html.twig', [
                'reservation_dechet' => $reservationDechet,
            ]);
        }

        #[Route('/{id}/edit', name: 'app_reservation_dechets_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, ReservationDechets $reservationDechet, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(ReservationDechetsType::class, $reservationDechet);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_reservation_dechets_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('reservation_dechets/edit.html.twig', [
                'reservation_dechet' => $reservationDechet,
                'form' => $form,
            ]);
        }

        #[Route('/{id}', name: 'app_reservation_dechets_delete', methods: ['POST'])]
        public function delete(Request $request, ReservationDechets $reservationDechet, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$reservationDechet->getId(), $request->request->get('_token'))) {
                $entityManager->remove($reservationDechet);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_reservation_dechets_index', [], Response::HTTP_SEE_OTHER);
        }

    
    }
