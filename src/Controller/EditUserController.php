<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormError;

class EditUserController extends AbstractController
{
    #[Route('/edit/user', name: 'app_edit_user')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager, Security $security): Response
    {

        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);
        $user = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            if (
                strpos($form->get('entreprise')->get('adresse')->getData(), "undefined") === false &&
                strpos($form->get('entreprise')->get('cp')->getData(), "undefined")  === false &&
                strpos($form->get('entreprise')->get('ville')->getData(), "undefined") === false
                ){
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
                if ($form->get('plainPassword')->getData()){
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                            )
                    );
                }
                
                if ($form->get('entreprise')->getData()) {
                    $user->setRoles(['ROLE_ENTREPRISE']);
                } else {
                    $user->setRoles(['ROLE_USER']);
                }

                $entityManager->persist($user);
                $entityManager->flush();

                
                return $this->redirectToRoute('app_main');

            } else {
                $error = new FormError('Adress not compleate !');
                $form->addError($error);
            }
        }
        if ($form->isSubmitted()) {
        $error = new FormError('Problem found with your registration éléments.');
        $form->addError($error);
    }
        return $this->render('edit_user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
