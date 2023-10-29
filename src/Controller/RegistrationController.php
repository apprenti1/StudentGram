<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Entreprise;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormError;



class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profilePictureFile = $form->get('photo_profil')->getData();
            if ($profilePictureFile) {
                // Convertir l'image en base64
                $profilePictureBase64 =
                    "data:image/".
                    $profilePictureFile->guessExtension().
                    ";base64,".
                    base64_encode(file_get_contents($profilePictureFile))
                ;
                $user->setPhotoProfil($profilePictureBase64);
            }

            
            
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                    )
            );
                
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        if (
                $form->isSubmitted() &&
                (!$security->isGranted('IS_AUTHENTICATED_FULLY'))
            ) {
            $error = new FormError('Problem found with your registration éléments.');
            $form->addError($error);
        }

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'registrationForm1' => $form->get("entreprise")->getData(),
            'registrationForm2' => $user,
            'file' => $_FILES,
        ]);
    }
}
