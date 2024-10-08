<?php

namespace App\Controller\Admin;

use App\Controller\AccueilController;
use App\Entity\Message;
use App\Entity\Prestation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableauBordController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //return $this->redirect($adminUrlGenerator->setController('app_admin_crud'::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
            
        $sales = 145; // Exemple
        $revenue = 3264;
        $customers = 1244;

        return $this->render('admin/dashboard.html.twig', [
            'sales' => $sales,
            'revenue' => $revenue,
            'customers' => $customers,
        ]);
        return $this->render('Admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/images/logo.PNG" alt="Logo" style="max-height: 300px;">');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Prestation::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-message', Message::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shop', Message::class);
        yield MenuItem::linkToCrud('Promotions', 'fas fa-percent', Message::class);


        yield MenuItem::section('Mode');
        yield MenuItem::linkToRoute('Passer en mode Client', 'fas fa-user-tie', 'app_user_page');
        yield MenuItem::linkToRoute('Accueil du site', 'fas fa-globe', 'app_accueil');

    }
}
