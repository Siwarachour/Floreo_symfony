<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\DechetsRepository;
use App\Entity\Dechets;
use App\Form\DechetsType;

use App\Entity\ReservationDechets;
use App\Form\ReservationDechetsType;
use App\Repository\ReservationDechetsRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('Base-front.html.twig', [
            'controller_name' => 'MainController',
        ]);
        
    }

    #[Route('/listedechets', name: 'product_list')]
    public function find(DechetsRepository $productRepository): Response
    {
        $dechet = $productRepository->findAll();

        // Now you can pass $products to a template, or return a JSON response, etc.
        return $this->render('front/list_dechet.html.twig', [
            'dechet' => $dechet,
        ]);
    }


    #[Route('/reserver/{id}', name: 'app_reserver')]
    public function new(Security $security,Request $request, EntityManagerInterface $entityManager , DechetsRepository $dechets , $id ): Response
    {

      $user = $security->getUser();


        $dechet = $dechets->find($id);
        $reservationDechet = new ReservationDechets();
        $form = $this->createForm(ReservationDechetsType::class, $reservationDechet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $reservationDechet->setUser($user);
          $reservationDechet->setNomFournisseur($user->getUsername());
            //////////////////////////// mail 
            require '../vendor/autoload.php';
            $mail = new PHPMailer(true);
            //Server settings                    //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->Port = 2525 ;                    //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = '08858a37329eeb';                     //SMTP username
            $mail->Password   = '0674fda6c65990';                               //SMTP password
            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('to@example.com', 'Joe User');
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Welcome to our website!';
            $mail->Body = '
            <html>
            <head>
              <title>Confirmation de Reservation</title>
              <style>
                body {
                  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                  margin: 0;
                  padding: 0;
                  background-color: #f3f3f3;
                }
                .container {
                  max-width: 600px;
                  margin: 40px auto;
                  padding: 20px;
                  background-color: #ffffff;
                  border-radius: 8px;
                  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
                }
                h1 {
                  color: #007BFF;
                  text-align: center;
                }
                p {
                  color: #4a4a4a;
                  font-size: 16px;
                  line-height: 1.5;
                  margin: 16px 0;
                }
                .footer {
                  text-align: center;
                  margin-top: 20px;
                  font-size: 12px;
                  color: #aaaaaa;
                }
              </style>
            </head>
            <body>
              <div class="container">
                <h1>Confirmation de Reservation</h1>
                <p>Votre reservation a ete bien effectuee.</p>
                <div class="footer">
                  Merci de choisir nos services.
                </div>
              </div>
            </body>
            </html>';
            
            $mail->AltBody = 'enjoy your time with us!';
            $mail->send();




            $entityManager->persist($reservationDechet);
            $entityManager->flush();
            return $this->redirectToRoute('product_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/reserver.html.twig', [
            'reservation_dechet' => $reservationDechet,
            'dechet' => $dechet ,
            'form' => $form,
        ]);
    }
    
    
    
}
