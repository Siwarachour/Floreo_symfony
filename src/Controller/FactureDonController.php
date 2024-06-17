<?php

namespace App\Controller;

use App\Entity\FactureDon;
use App\Form\FactureDonType;
use App\Repository\FactureDonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Dompdf\Dompdf;



#[Route('/facture/don')]
class FactureDonController extends AbstractController
{


    #[Route('/pdf', name: 'pdffacture', methods: ['GET'])]
    public function index_pdf(FactureDonRepository $factureDonRepository, Request $request): Response
    {
        // Création d'une nouvelle instance de la classe Dompdf
        $dompdf = new Dompdf();

        // Récupération des top 3 commandes par total price
        $factureDons = $factureDonRepository->findAll();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/Front/img/facture.png';
        // Encode the image to base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        // Génération du HTML à partir du template Twig 'commande/pdf_file.html.twig' en passant les top 3 commandes
        $html = $this->renderView('facturedonback/pdf_file.html.twig', [
            'factureDons' => $factureDons,
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


    #[Route('/', name: 'app_facture_don_index', methods: ['GET'])]
    public function index(FactureDonRepository $factureDonRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $perPage = 5;
        $currentPage = $request->query->getInt('page', 1);

        // Créer une requête pour récupérer les dons
        $query = $entityManager->createQueryBuilder()
            ->select('f')
            ->from('App\Entity\FactureDon', 'f')
            ->getQuery();

        // Paginer les résultats
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($currentPage - 1))
            ->setMaxResults($perPage);

        // Récupérer les dons pour la page actuelle
        $facture_dons = $paginator->getQuery()->getResult();

        // Calculer le nombre total de pages
        $totalPages = ceil(count($paginator) / $perPage);
        return $this->render('facture_don/index.html.twig', [
            'facture_dons' => $facture_dons,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
        ]);
    }
    

    #[Route('/new', name: 'app_facture_don_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $factureDon = new FactureDon();
        $form = $this->createForm(FactureDonType::class, $factureDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /////////////////////////////////
            require '../vendor/autoload.php';
            $mail = new PHPMailer(true);                  //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->Port = 2525 ;                    //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '7288397d868a11';                     //SMTP username
            $mail->Password   = 'b36853c3d7189d';                               //SMTP password
            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('from@example.com', 'Mailer');
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Welcome to our website!';
            $mail->Body = 'welcome';
            $mail->AltBody = 'enjoy your time with us!';
            $mail->send();




            //////////////////////////////////
            $entityManager->persist($factureDon);
            $entityManager->flush();

            return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture_don/new.html.twig', [
            'facture_don' => $factureDon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_don_show', methods: ['GET'])]
    public function show(FactureDon $factureDon): Response
    {
        return $this->render('facture_don/show.html.twig', [
            'facture_don' => $factureDon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_don_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureDon $factureDon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureDonType::class, $factureDon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture_don/edit.html.twig', [
            'facture_don' => $factureDon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_don_delete', methods: ['POST'])]
    public function delete(Request $request, FactureDon $factureDon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factureDon->getId(), $request->request->get('_token'))) {
            $entityManager->remove($factureDon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
