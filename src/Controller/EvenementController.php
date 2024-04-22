<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('admin/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/valide/{id}',name: 'admin-evenement-valide')]
    public function valider($id, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager):Response{

        $evenement = $evenementRepository->find($id);
        if ($evenement && $this->isGranted('ROLE_ADMIN')) {

            $evenement->setValide(true);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_evenement_index');
    }

    #[Route('/', name: 'app_evenement_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->get('valid');
            if ($data && is_array($data)) {

                foreach ($data as $id => $valid) {
                    $evenement = $evenementRepository->find($id);

                    if ($evenement && $this->isGranted('ROLE_ADMIN')) {
                        $evenement->setValide($valid === 'on');
                        $entityManager->persist($evenement);
                    }
                }

                $entityManager->flush();
            }
        }

        return $this->render('admin/evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
{
    $evenement = new Evenement();
    $form = $this->createForm(EvenementType::class, $evenement, ['is_admin' => true]); // Pass is_admin as true
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $selectedSalle = $form->get('salle')->getData();
        $evenement->setSalle($selectedSalle);
        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('app_evenement_index');
    }

    return $this->render('admin/evenement/new.html.twig', [
        'evenement' => $evenement,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('admin/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
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

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
