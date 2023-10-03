<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreFormType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/registration/offer')]
class RegistrationOfferController extends AbstractController
{
    #[Route('/', name: 'app_registration_offer_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('registration_offer/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_registration_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            return $this->redirectToRoute('app_registration_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration_offer/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);

        return $this->render('registration_offer/new.html.twig', [
            'offreForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_registration_offer_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('registration_offer/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_registration_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreFormType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_registration_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration_offer/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registration_offer_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registration_offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
