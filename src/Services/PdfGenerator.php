<?php

namespace App\Services;

use App\Entity\Commande;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    public function generateInvoicePdf(Commande $commande): string
    {
        $pdf = new Dompdf();
        $html = $this->renderInvoiceHtml($commande); // Une méthode pour générer le HTML

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        // Spécifiez un répertoire valide pour enregistrer le PDF
        $outputDir = __DIR__ . '/../../var/invoices'; // Ajustez selon votre structure
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true); // Créez le répertoire s'il n'existe pas
        }

        $pdfFilePath = $outputDir . '/invoice.pdf'; // Chemin où vous souhaitez sauvegarder le PDF
        file_put_contents($pdfFilePath, $pdf->output());

        return $pdfFilePath; // Retourner le chemin du fichier PDF
    }

    private function renderInvoiceHtml(Commande $commande): string
    {
        // HTML et CSS pour la facture
        return '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Facture</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f4f4f4;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    text-align: center;
                    color: #333;
                }
                .details, .summary {
                    margin: 20px 0;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 15px;
                }
                .details h2, .summary h2 {
                    font-size: 1.5em;
                    margin-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                }
                th, td {
                    border: 1px solid #ccc;
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f9f9f9;
                }
                .total {
                    font-weight: bold;
                    font-size: 1.2em;
                    margin-top: 10px;
                    text-align: right;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Facture pour ' . $commande->getClient()->getEmail() . '</h1>
                <p>Date de commande: ' . $commande->getDateCommande()->format('d/m/Y') . '</p>

                <div class="details">
                    <h2>Détails de la commande</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID de la prestation</th>
                                <th>Nom de la prestation</th>
                                <th>Prix unitaire (MGA)</th>
                                <th>Quantité</th>
                                <th>Total (MGA)</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach ($commande->getPrestations() as $prestation) {
            $html .= '<tr>
                        <td>' . $prestation->getId() . '</td>
                        <td>' . $prestation->getNom() . '</td>
                        <td>' . number_format($prestation->getPrix(), 2, ',', ' ') . '</td>
                        <td>' . $prestation->getQuantite() . '</td>
                        <td>' . number_format($prestation->getPrix() * $prestation->getQuantite(), 2, ',', ' ') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </div>

        <div class="summary">
            <h2>Récapitulatif</h2>
            <p class="total">Montant total: ' . number_format($commande->getMontantTotal(), 2, ',', ' ') . ' MGA</p>
        </div>
    </div>
</body>
</html>';
    }
}
