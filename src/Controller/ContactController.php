<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $messageContent = $request->request->get('message');

            $validator = Validation::createValidator();
            $emailConstraint = new EmailConstraint();
            $notBlankConstraint = new NotBlank();

            $errors = $validator->validate($email, [$emailConstraint, $notBlankConstraint]);

            if (count($errors) > 0) {
                $this->addFlash('error', 'Veuillez entrer une adresse email valide.');
                return $this->redirectToRoute('contact');
            }
            //mail 
            $emailMessage = (new TemplatedEmail())
            ->from($email)
            ->to('kellymanambina@gmail.com')
            ->subject($subject)
            ->htmlTemplate('emails/contact_email.html.twig')
            ->context([
                'name' => $name,
                'userEmail' => $email,
                'subject' => $subject,
                'messageContent' => $messageContent,
            ]);

            try {
                $mailer->send($emailMessage);
            
                //base de données
                $message = new Message();
                $message->setName($name);
                $message->setEmail($email);
                $message->setSubject($subject);
                $message->setMessage($messageContent);
                $message->setCreatedAt(new \DateTime());
            
                $entityManager->persist($message);
                $entityManager->flush();
            
                $this->addFlash('success', 'Votre message a été envoyé avec succès!');
            } catch (\Exception $e) { 
                dd($e->getMessage()); // Affiche le message d'erreur spécifique
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
            }
                    

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig');
    }
}
