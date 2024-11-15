<?php
namespace App\Services;

use App\Entity\Commande;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class InvoiceMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvoice(Commande $commande, string $pdfFilePath): void
    {
        // Récupérer le client via l'entité User associée
        $client = $commande->getClient();
        $clientEmail = $client->getEmail();

        $email = (new TemplatedEmail())
            ->from('kellymanambina@gmail.com')
            ->to($clientEmail)
            ->subject('Votre facture - Merci de votre confiance')
            ->htmlTemplate('emails/facture_email.html.twig')
            ->attachFromPath($pdfFilePath, 'facture.pdf');
    
        $this->mailer->send($email);
    }
}
