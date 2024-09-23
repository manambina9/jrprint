<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCrudController extends AbstractController
{
    #[Route('/admin/crud', name: 'app_admin_crud')]
    public function index(): Response
    {
        return $this->render('admin_crud/index.html.twig', [
            'controller_name' => 'AdminCrudController',
        ]);
    }
}
