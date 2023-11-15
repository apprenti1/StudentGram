<?php

namespace App\Controller;

use App\Entity\TypeOffreEmploi;
use App\Form\TypeOffreEmploiType;
use App\Repository\TypeOffreEmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/offre/emploi')]
class TypeOffreEmploiController extends AbstractController
{
    #[Route('/', name: 'app_type_offre_emploi_index', methods: ['GET'])]
    public function index(TypeOffreEmploiRepository $typeOffreEmploiRepository): Response
    {
        return $this->render('type_offre_emploi/index.html.twig', [
            'type_offre_emplois' => $typeOffreEmploiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_offre_emploi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeOffreEmploi = new TypeOffreEmploi();
        $form = $this->createForm(TypeOffreEmploiType::class, $typeOffreEmploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeOffreEmploi);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_offre_emploi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_offre_emploi/new.html.twig', [
            'type_offre_emploi' => $typeOffreEmploi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_offre_emploi_show', methods: ['GET'])]
    public function show(TypeOffreEmploi $typeOffreEmploi): Response
    {
        return $this->render('type_offre_emploi/show.html.twig', [
            'type_offre_emploi' => $typeOffreEmploi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_offre_emploi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeOffreEmploi $typeOffreEmploi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeOffreEmploiType::class, $typeOffreEmploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_offre_emploi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_offre_emploi/edit.html.twig', [
            'type_offre_emploi' => $typeOffreEmploi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_offre_emploi_delete', methods: ['POST'])]
    public function delete(Request $request, TypeOffreEmploi $typeOffreEmploi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeOffreEmploi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeOffreEmploi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_offre_emploi_index', [], Response::HTTP_SEE_OTHER);
    }
}
