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
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager, 
        MailerInterface $mailer, 
        LoggerInterface $logger
    ): Response {
        $demande = new DemandePrestation();
        $form = $this->createForm(DemandePrestationType::class, $demande);
        $form->handleRequest($request);
    
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $nom = $request->request->get('nom');
            $societe = $request->request->get('societe');
            $email = $request->request->get('email');
            $telephone = $request->request->get('telephone');
            $adresse = $request->request->get('adresse');
            $codePostal = $request->request->get('code_postal');
            $ville = $request->request->get('ville');
            $prestation = $request->request->get('prestation');
            $message = $request->request->get('message');
    
            // Création de l'email
            $emailMessage = (new Email())
            ->from($email)
            ->to('ramanamahefafabrice@gmail.com')
            ->subject('Demande de Prestation - ' . $societe)
            ->text("
            Bonjour,
        
            Nous avons le plaisir de vous informer que nous avons reçu une nouvelle demande de prestation de la part de $nom représentant $societe. 
        
            Vous trouverez ci-dessous les informations concernant cette demande :
            
            Le client, $nom, peut être contacté à l'adresse email $email ou par téléphone au $telephone. Il réside à $adresse, $codePostal $ville.
        
            La prestation demandée est la suivante : $prestation.  
            Voici également un message complémentaire du client :  
            \"$message\".
        
            Nous vous remercions de bien vouloir traiter cette demande et restons à votre disposition pour toute information complémentaire.
        
            Cordialement,
            ");
        
    
            try {
                // Envoi de l'email
                $mailer->send($emailMessage);
                // Message de succès
                $this->addFlash('success', 'Votre demande a été envoyée avec succès !');
            } catch (\Exception $e) {
                // Log de l'erreur et message d'échec
                $logger->error('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer plus tard.');
            }
        }
    
        // Afficher le formulaire et les messages flash si nécessaire
        return $this->render('demande_prestation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}