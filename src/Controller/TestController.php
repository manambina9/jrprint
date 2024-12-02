<?php

// src/Controller/TestController.php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User; // Assurez-vous d'importer la bonne entité
use App\Services\CommandeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    #[Route('/simuler-commande', name: 'simuler_commande')]
    public function simulerCommande(): Response
    {
        // Créer une nouvelle commande
        $commande = new Commande();
 
        $client = new User();
        $client->setEmail('testclient@example.com');  

        // Définir des valeurs pour la commande
        $commande->setClient($client);
        $commande->setDetailCommande('Location d\'un panneau 12*3');
        $commande->setMontantTotal(500.00); // Montant total pour la simulation
        $commande->setDateDebutLocation(new \DateTimeImmutable());
        $commande->setDateFinLocation((new \DateTimeImmutable())->modify('+1 month'));

        // Mettre à jour le statut à "livrée" pour déclencher l'envoi de la facture
        $this->commandeService->updateCommandeStatut($commande, 'livrée');

        return new Response('Commande simulée et facture envoyée!');
    }
}
