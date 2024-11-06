<?php
namespace App\Services;
// src/Services/InvoiceMailer.php

use App\Entity\Commande;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class InvoiceMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendInvoice(Commande $commande, string $pdfFilePath): void
    {
        $email = (new Email())
            ->from('kellymanambina@gmail.com') // Remplacez par votre adresse e-mail
            ->to($commande->getClient()->getEmail())
            ->subject('Votre facture')
            ->text('Merci pour votre commande. Vous trouverez ci-joint la facture.')
            ->attachFromPath($pdfFilePath, 'facture.pdf');

        // Envoyer l'e-mail
        $this->mailer->send($email);
    }
}
