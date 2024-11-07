<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use App\Entity\Commande; 

class AlerteEmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function envoyerAlerte(string $email, Commande $commande)
    {
        $message = (new Email())
            ->from('kellymanambina@gmail.com')
            ->to($email)
            ->subject('Alerte de Location')
            ->html($this->twig->render('emails/alerte_location.html.twig', [
                'commande' => $commande
            ]));

        $this->mailer->send($message);
    }
}
