<?php
namespace App\Services;

use App\Entity\Commande;
use App\Services\PdfGenerator;
use App\Services\InvoiceMailer;
use Doctrine\ORM\EntityManagerInterface;

class CommandeService
{
    private $entityManager;
    private $pdfGenerator;
    private $invoiceMailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        PdfGenerator $pdfGenerator,
        InvoiceMailer $invoiceMailer
    ) {
        $this->entityManager = $entityManager;
        $this->pdfGenerator = $pdfGenerator;
        $this->invoiceMailer = $invoiceMailer;
    }

    public function updateCommandeStatut(Commande $commande, string $statut): void
    {
        $commande->setStatut($statut);

        if ($statut === 'livrée') {
            // Génération de la facture PDF
            $pdf = $this->pdfGenerator->generateInvoicePdf($commande);
            
            // Envoi de la facture par email
            $this->invoiceMailer->sendInvoice($commande, $pdf);
        }

        // Sauvegarder la commande dans la base de données
        $this->entityManager->flush();
    }
}
