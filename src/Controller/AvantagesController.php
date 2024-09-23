<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvantagesController extends AbstractController
{
    #[Route('/avantages', name: 'Avantages')]
    public function index(): Response
    {
        return $this->render('avantages/index.html.twig', [
            'controller_name' => 'AvantagesController',
        ]);
    }
}
