<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UpdateprofileType;
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Fetch the authenticated user
        $user = $this->getUser();

        // Render the profile page template and pass the user information
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
   
    
    #[Route('/profile/update', name: 'app_profile_update')]
    public function update(Request $request): Response
    {
        // Fetch the authenticated user
        $user = $this->getUser();

        // Create the form using UserType and the existing user
        $form = $this->createForm(UpdateprofileType::class, $user);

        // Handle form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect the user to another page, e.g., the profile page
            return $this->redirectToRoute('app_profile');
        }

        // If the form is not submitted or not valid, simply pass the form to the Twig template
        return $this->render('user/updateprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
