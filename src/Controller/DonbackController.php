<?php

namespace App\Controller;

use App\Entity\Don;
use App\Form\Don1Type;
use App\Repository\DonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donback')]
class DonbackController extends AbstractController
{
    #[Route('/', name: 'app_donback_index', methods: ['GET'])]
    public function index(DonRepository $donRepository, EntityManagerInterface $entityManager): Response
{
    $donStatistics = $entityManager->createQueryBuilder()
       ->select('d.type, COUNT(d.id) as donCount')
       ->from('App\Entity\Don', 'd')
       ->groupBy('d.type')
       ->getQuery()
       ->getResult();

    return $this->render('donback/index.html.twig', [
        'dons' => $donRepository->findAll(),
        'donStatistics' =>  $donStatistics,
    ]);
}


    #[Route('/new', name: 'app_donback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $don = new Don();
        $form = $this->createForm(Don1Type::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($don);
            $entityManager->flush();

            return $this->redirectToRoute('app_donback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donback/new.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donback_show', methods: ['GET'])]
    public function show(Don $don): Response
    {
        return $this->render('donback/show.html.twig', [
            'don' => $don,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Don $don, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Don1Type::class, $don);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donback/edit.html.twig', [
            'don' => $don,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donback_delete', methods: ['POST'])]
    public function delete(Request $request, Don $don, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$don->getId(), $request->request->get('_token'))) {
            $entityManager->remove($don);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donback_index', [], Response::HTTP_SEE_OTHER);
    }
}
