<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home'); // Remplacez 'app_home' par votre route principale
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);

            // Sauvegarder les autres données (les champs supplémentaires)
            $user->setAdresse($form->get('adresse')->getData());
            $user->setNif($form->get('nif')->getData());
            $user->setStat($form->get('stat')->getData());
            $user->setCif($form->get('cif')->getData());
            $user->setRcs($form->get('rcs')->getData());
            $user->setRc($form->get('rc')->getData());
            $user->setAgreeTerms($form->get('agreeTerms')->getData());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
