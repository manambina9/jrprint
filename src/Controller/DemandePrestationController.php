<?php

namespace App\Controller;

use App\Entity\User;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/formulaire-prestation', name: 'formulaire_prestation')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager, 
        MailerInterface $mailer, 
        LoggerInterface $logger,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $demande = new DemandePrestation();
        $form = $this->createForm(DemandePrestationType::class, $demande);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            // Récupération des données du formulaire
            $nom = $request->request->get('nom');
            $societe = $request->request->get('societe');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $passwordConfirmation = $request->request->get('password_confirmation');
            $telephone = $request->request->get('telephone');
            $adresse = $request->request->get('adresse');
            $codePostal = $request->request->get('code_postal');
            $ville = $request->request->get('ville');
            $prestation = $request->request->get('prestation');
            $message = $request->request->get('message');

            // Vérification de l'email et du mot de passe
            if (empty($email)) {
                $this->addFlash('error', 'L\'adresse e-mail est obligatoire.');
                return $this->redirectToRoute('formulaire_prestation');
            }

            if ($password !== $passwordConfirmation) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('formulaire_prestation');
            }

            // Création de l'utilisateur
            $user = new User();
            $user->setNom($nom);
            $user->setEmail($email);
            $user->setTelephone($telephone);
            $user->setAdresse($adresse);
            $user->setCodePostal($codePostal);
            $user->setVille($ville);
            $user->setEntreprise($societe); // Assurez-vous que $societe n'est pas null
            $user->setPassword($passwordHasher->hashPassword($user, $password));

            // Enregistrement dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Création et envoi de l'email
            $emailMessage = (new Email())
                ->from($email)
                ->to('ramanamahefafabrice@gmail.com')
                ->subject('Demande de Prestation - ' . $societe)
                ->text("
                Bonjour,

                Nous avons le plaisir de vous informer que nous avons reçu une nouvelle demande de prestation de la part de $nom représentant $societe.

                Voici les détails :
                Nom : $nom
                Société : $societe
                Email : $email
                Téléphone : $telephone
                Adresse : $adresse, $codePostal $ville
                Prestation demandée : $prestation

                Message :
                \"$message\".

                Cordialement,
                ");

            try {
                $mailer->send($emailMessage);
                $this->addFlash('success', 'Votre demande a été envoyée et enregistrée avec succès !');
            } catch (\Exception $e) {
                $logger->error('Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre demande.');
            }

            return $this->redirectToRoute('formulaire_prestation');
        }

        return $this->render('demande_prestation/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
