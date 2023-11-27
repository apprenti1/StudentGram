<?php

namespace App\Controller;
use App\Repository\OffreRepository;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        OffreRepository     $offresrespo,
        EvenementRepository $evenementrepo,
        UserRepository      $userRepository,
        Security            $security
    ): Response
    {
        if ($security->isGranted("ROLE_ADMIN")) {
            $users = $userRepository->findAll();
        }
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'offres' => $offresrespo->findAll(),
            'evenements' => $evenementrepo->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }
}
