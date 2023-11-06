<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalleController extends AbstractController
{
    #[Route('/salles', name: 'app_salle_list')]
    public function list(): Response
    {
        // Ajoutez ici la logique pour récupérer et afficher la liste des salles depuis la base de données
        return $this->render('salle/list.html.twig', [
            'controller_name' => 'SalleController',
        ]);
    }

    #[Route('/salle/{id}', name: 'app_salle_detail')]
    public function detail($id): Response
    {
        // Ajoutez ici la logique pour récupérer et afficher les détails d'une salle en fonction de son ID
        return $this->render('salle/detail.html.twig', [
            'controller_name' => 'SalleController',
            'salle_id' => $id,
        ]);
    }

    // Vous pouvez ajouter d'autres actions personnalisées en fonction de vos besoins

    // Exemple d'une action pour affecter une salle à un événement
    #[Route('/salle/{id}/assign', name: 'app_salle_assign')]
    public function assignSalle($id): Response
    {
        // Ajoutez ici la logique pour affecter une salle à un événement
        return $this->redirectToRoute('app_salle_list');
    }
}
