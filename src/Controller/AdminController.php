<?php

namespace App\Controller;


use App\Entity\Salle;
use App\Entity\TypeContrat;
use App\Entity\Offre;
use App\Entity\User;
use App\Entity\Evenement;
use App\Form\SalleType;
use App\Form\TypeContratType;
use App\Form\OffreType;
use App\Repository\EvenementRepository;
use App\Repository\SalleRepository;
use App\Repository\TypeContratRepository;
use App\Repository\OffreRepository;
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
    public function userIndex(
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
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

    #[Route('/user/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function userShow(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function userDelete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/offre', name: 'app_offre_index', methods: ['GET'])]
    public function offreIndex(OffreRepository $offreRepository): Response
    {
        return $this->render('admin/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/offre/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function offreNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/offre/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function offreShow(Offre $offre): Response
    {
        return $this->render('admin/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/offre/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function offreEdit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/offre/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function offreDelete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/typecontrat', name: 'app_type_contrat_index', methods: ['GET'])]
    public function typecontratIndex(TypeContratRepository $typeContratRepository): Response
    {
        return $this->render('admin/type_contrat/index.html.twig', [
            'type_contrats' => $typeContratRepository->findAll(),
        ]);
    }

    #[Route('/typecontrat/new', name: 'app_type_contrat_new', methods: ['GET', 'POST'])]
    public function typecontratNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeContrat = new TypeContrat();
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeContrat);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/type_contrat/new.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form,
        ]);
    }

    #[Route('/typecontrat/{id}', name: 'app_type_contrat_show', methods: ['GET'])]
    public function typecontratShow(TypeContrat $typeContrat): Response
    {
        return $this->render('admin/type_contrat/show.html.twig', [
            'type_contrat' => $typeContrat,
        ]);
    }

    #[Route('/typecontrat/{id}/edit', name: 'app_type_contrat_edit', methods: ['GET', 'POST'])]
    public function typecontratEdit(Request $request, TypeContrat $typeContrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/type_contrat/edit.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form,
        ]);
    }

    #[Route('/typecontrat/{id}', name: 'app_type_contrat_delete', methods: ['POST'])]
    public function typecontratDelete(Request $request, TypeContrat $typeContrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeContrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeContrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_contrat_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/salle', name: 'app_salle_index', methods: ['GET'])]
    public function salleIndex(SalleRepository $salleRepository): Response
    {
        return $this->render('admin/salle/index.html.twig', [
            'salles' => $salleRepository->findAll(),
        ]);
    }

    #[Route('/salle/new', name: 'app_salle_new', methods: ['GET', 'POST'])]
    public function salleNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/salle/new.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/salle/{id}', name: 'app_salle_show', methods: ['GET'])]
    public function salleShow(Salle $salle): Response
    {
        return $this->render('admin/salle/show.html.twig', [
            'salle' => $salle,
        ]);
    }

    #[Route('/salle/{id}/edit', name: 'app_salle_edit', methods: ['GET', 'POST'])]
    public function salleEdit(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form,
        ]);
    }

    #[Route('/salle/{id}', name: 'app_salle_delete', methods: ['POST'])]
    public function salleDelete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/evenement', name: 'app_evenement_index', methods: ['GET'])]
    public function evenementIndex(EvenementRepository $evenementRepository): Response
    {
        return $this->render('admin/evenement/index.html.twig', [
            'evenement' => $evenementRepository->findAll(),
        ]);
    }

    #[Route('/evenement/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function evenementNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salleId = $request->request->get('salle');
            $salle = $this->salleRepository->find($salleId);


            if ($salle) {
                $evenement->setSalle($salle);
                $entityManager->persist($evenement);
                $entityManager->flush();


                return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('admin/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
            'salles' => $this-> salleRepository->findAll(),
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function evenementShow(evenement $evenement): Response
    {
        return $this->render('admin/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/evenement/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function evenementEdit(Request $request, evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(evenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/evenement/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function evenementDelete(Request $request, evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement', [], Response::HTTP_SEE_OTHER);
    }

}
