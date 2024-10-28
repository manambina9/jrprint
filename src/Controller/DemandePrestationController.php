<?php

namespace App\Controller;

use App\Entity\DemandePrestation;
use App\Form\DemandePrestationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/formulaire-prestation', name: 'formulaire_prestation')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $demande = new DemandePrestation();
        $form = $this->createForm(DemandePrestationType::class, $demande);

        // Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Enregistrer la demande dans la base de données
                $entityManager->persist($demande);
                $entityManager->flush();

                // Envoi d'un email de confirmation à l'utilisateur
                $email = (new Email())
                    ->from('contact@jrprint.com')
                    ->to($demande->getEmail())  // Assurez-vous que le champ email existe
                    ->subject('Confirmation de votre demande de prestation')
                    ->text('Merci pour votre demande. Nous vous contacterons bientôt.')
                    ->html('<p>Merci pour votre demande. Nous vous contacterons bientôt.</p>');

                $mailer->send($email);

                // Logger l'événement
                $logger->info('Nouvelle demande de prestation reçue : '.$demande->getId());

                // Redirection après la soumission réussie
                return $this->redirectToRoute('formulaire_prestation_success');

            } catch (\Exception $e) {
                // Gestion des erreurs en cas d'échec de la base de données ou d'autres exceptions
                $logger->error('Erreur lors de la soumission de la demande : '.$e->getMessage());

                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement de votre demande. Veuillez réessayer plus tard.');
            }
        }

        return $this->render('demande_prestation/index.html.twig', [
            'form' => $form->createView(),
        ]);
        dd($demande);
    }

    #[Route('/formulaire-prestation/success', name: 'formulaire_prestation_success')]
    public function success(): Response
    {
        // Renvoyer la vue Twig avec la variable message
        return $this->render('demande_prestation/success.html.twig', [
            'message' => 'Merci pour votre demande. Nous vous contacterons sous peu.'
        ]);
    }

}
