<?php

namespace App\Controller;

use App\Entity\ReponseOffre;
use App\Form\ReponseOffreType;
use App\Repository\ReponseOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse/offre')]
class ReponseOffreController extends AbstractController
{
    #[Route('/', name: 'app_reponse_offre_index', methods: ['GET'])]
    public function index(ReponseOffreRepository $reponseOffreRepository): Response
    {
        return $this->render('reponse_offre/index.html.twig', [
            'reponse_offres' => $reponseOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reponse_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponseOffre = new ReponseOffre();
        $form = $this->createForm(ReponseOffreType::class, $reponseOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponseOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse_offre/new.html.twig', [
            'reponse_offre' => $reponseOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_offre_show', methods: ['GET'])]
    public function show(ReponseOffre $reponseOffre): Response
    {
        return $this->render('reponse_offre/show.html.twig', [
            'reponse_offre' => $reponseOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReponseOffre $reponseOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseOffreType::class, $reponseOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse_offre/edit.html.twig', [
            'reponse_offre' => $reponseOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_offre_delete', methods: ['POST'])]
    public function delete(Request $request, ReponseOffre $reponseOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseOffre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponseOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
