<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserPageController extends AbstractController
{
    #[Route('/user/page', name: 'app_user_page')]
    public function index(): Response
    {

        $superadmin = ["ROLE_SUPER_ADMIN", "ROLE_ADMIN", "ROLE_USER"];
        $admin = ["ROLE_ADMIN", "ROLE_USER"];
        $user = [];

        return $this->render('user_page/index.html.twig', [
            'controller_name' => 'UserPageController',
        ]);
    }
}
