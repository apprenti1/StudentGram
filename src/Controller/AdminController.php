<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Admin\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/user', name: 'app_admin_user_index')]
    public function index(
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
        ): Response
    {
        $validlist = $request->get('valid');
        $adminlist = $request->get('admin');
        if (isset($validlist)) {
            foreach ($validlist as $id => $isvalid) {
                $user = $userRepository->find($id);
                $roles = $user->getRoles();
                if (!in_array("ROLE_ADMIN", $roles) || $security->isGranted("ROLE_SUPERADMIN")) {
                    if ($isvalid == "on" && !in_array("ROLE_VALID", $roles)) {
                        $user->setRoles(array_merge($roles, ["ROLE_VALID"]));
                    } elseif ($isvalid != "on" && in_array("ROLE_VALID", $roles)) {
                        $user->setRoles(array_diff($roles, ["ROLE_VALID"]));
                    }
                }
            }
        }
        if (isset($validlist) && $security->isGranted("ROLE_SUPERADMIN")) {
            foreach ($adminlist as $id => $isadmin) {
                $user = $userRepository->find($id);
                $roles = $user->getRoles();
                if ($isadmin == "on" && !in_array("ROLE_ADMIN", $roles)) {
                    $user->setRoles(array_merge($roles, ["ROLE_ADMIN"]));
                } elseif ($isadmin != "on" && in_array("ROLE_ADMIN", $roles)) {
                    $user->setRoles(array_diff($roles, ["ROLE_ADMIN"]));
                }
            }
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
