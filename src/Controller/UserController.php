<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\EditUserType;



class UserController extends AbstractController
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
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);

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
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                        )
                );
                
                if ($form->get('entreprise')->getData()) {
                    $user->setRoles(['ROLE_ENTREPRISE']);
                } else {
                    $user->setRoles(['ROLE_ETUDIANT']);
                }

                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email
                if (!$security->isGranted('IS_AUTHENTICATED_FULLY')){
                    return $userAuthenticator->authenticateUser(
                        $user,
                        $authenticator,
                        $request
                    );
                } else {
                    return $this->redirectToRoute('app_admin_user_index');
                }
            } else {
                $error = new FormError('Adress not compleate !');
                $form->addError($error);
            }
        }

        if (
                $form->isSubmitted() &&
                (!$security->isGranted('IS_AUTHENTICATED_FULLY'))
            ) {
            $error = new FormError('Problem found with your registration éléments.');
            $form->addError($error);
        }

        if ($security->isGranted('IS_AUTHENTICATED_FULLY') && (!($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPERADMIN')) && isset($_POST['new']) && isset($_POST['new']) == true)) {
            return $this->redirectToRoute('app_main');
        }

        return $this->render('User/register.html.twig', [
            'registrationForm' => $form->createView(),
            'file' => $_FILES,
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_main');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('User/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }

    #[Route('/edit/user', name: 'app_edit_user')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        Security $security
        ): Response
    {

        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

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
        return $this->render('User/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'app_delete')]
    public function index(EntityManagerInterface $entityManager, FlashBagInterface $flashBag): Response
    {

        $user = $this->getUser();
        if (!$user) {
            $flashBag->add('warning', 'Vous devez être connecté pour supprimer votre compte.');
        } else {
            $entityManager->remove($user);
            $entityManager->flush();
            $flashBag->add('success', 'Votre compte a été supprimé avec succès.');
        }
        return $this->redirectToRoute('app_main');
    }
}
