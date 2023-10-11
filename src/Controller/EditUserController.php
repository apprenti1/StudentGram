<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends AbstractController
{
    #[Route('/edit/user', name: 'app_edit_user')]
    public function index(): Response
    {
        return $this->render('edit_user/index.html.twig', [
            'controller_name' => 'EditUserController',
        ]);
    }
}
