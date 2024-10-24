<?php

namespace App\Services;

use App\Repository\CommandeRepository;
use DateTimeImmutable;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private $commandeRepository;
    private $mailer;

    public function __construct(CommandeRepository $commandeRepository, MailerInterface $mailer)
    {
        $this->commandeRepository = $commandeRepository;
        $this->mailer = $mailer;
    }

    public function notifierAvantEcheance(): void
    {
        $dateLimite = (new DateTimeImmutable())->modify('+5 days');
        $commandes = $this->commandeRepository->findCommandesProchesEcheance($dateLimite);

        foreach ($commandes as $commande) {
            $this->envoyerNotification($commande);
        }
    }

    private function envoyerNotification($commande)
    {
        $email = (new Email())
            ->from('admin@tonsite.com')
            ->to('admin@tonsite.com')
            ->subject('Commande proche de l’échéance')
            ->text('La commande #' . $commande->getId() . ' arrive à échéance le ' . $commande->getDateFinLocation()->format('Y-m-d'));

        $this->mailer->send($email);
    }
}
