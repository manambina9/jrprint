<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request , MailerInterface $mailer): Response
    {
        if($request->isMethod('POST')){
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $subject = $request->request->get('subject');
            $message = $request->request->get('message');

            $validator = Validation::createValidator();
            $emailConstraint = new EmailConstraint();
            $notBlankConstraint = new NotBlank();

            $errors = $validator->validate($email, [$emailConstraint, $notBlankConstraint]);

            if (count($errors) > 0) {
                $errorMessage = (string) $errors;
                return new Response($errorMessage);

                $emailMessage = (new Email())
                ->from($email)
                ->to('kellyandell9@gmail.com')
                ->subject($subject)
                ->text(
                    "Name: $name\n".
                    "Email: $email\n".
                    "Message:\n$message"
                );

            $mailer->send($emailMessage);
            return $this->render('contact/index.html.twig', [
                'controller_name' => 'ContactController',
            ]);
        }

    }
    return $this->render('contact/index.html.twig');
}
}