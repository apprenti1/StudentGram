<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\User;
use App\Form\OffreFormType;
use App\Repository\OffreRepository;
use App\Repository\TypeContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/registration/offer')]
class OfferController extends AbstractController
{
    #[Route('/', name: 'app_offer', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        $offres = $offreRepository->findBy(['ref_entreprise' => $this->getUser()->getEntreprise()->getId()]);
        return $this->render('Offer/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('admin/new', name: 'app_offer_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        TypeContratRepository $contratRepository,
        EntityManagerInterface $entityManager
        ): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offre->setRefEntreprise($this->getUser()->getEntreprise());
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_offer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Offer/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
            'type_contrats' => $contratRepository->findAll(),
        ]);
    }

    #[Route('admin/offre/{id}', name: 'app_offer_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('Offer/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('admin/offre/{id}/edit', name: 'app_offer_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Offre $offre,
        EntityManagerInterface $entityManager
        ): Response
    {
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Offer/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offer', [], Response::HTTP_SEE_OTHER);
    }
}
