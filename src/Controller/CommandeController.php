<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Services\CommandeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    private $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    #[Route('/commande/{id}/livrer', name: 'commande_livrer')]
    public function livrerCommande(Commande $commande): Response
    {
        // Mettre à jour le statut de la commande
        $this->commandeService->updateCommandeStatut($commande, 'livrée');
        dd($commande);

        return $this->redirectToRoute('app_login'); // Redirection après traitement
    }
}
