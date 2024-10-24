<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Message;
use App\Entity\Commande;
use App\Entity\Promotion;
use App\Entity\Prestation;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Proxies\__CG__\App\Entity\Message as EntityMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class TableauBordController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function dashboard(UserRepository $userRepository): Response
    {
        // Récupérer les statistiques des utilisateurs par mois
        $usersByMonth = $userRepository->countUsersByMonth();
        //$sales = {{Entity.Command.Id}};
        // Variables de démonstration
        $sales = 145;
        $revenue = 3264; 
        $customers = 1244; 

        return $this->render('admin/dashboard.html.twig', [
            'sales' => $sales,
            'revenue' => $revenue,
            'customers' => $customers,
            'usersByMonth' => $usersByMonth,
        ]);
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
        yield MenuItem::linkToCrud('Services', 'fas fa-box', Prestation::class);
        yield MenuItem::linkToCrud('Messages', 'fas fa-message', Message::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shop', Commande::class); 
        yield MenuItem::linkToCrud('Promotions', 'fas fa-percent', Promotion::class);
        yield MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Facture::class);
        yield MenuItem::linkToCrud('Panneau dispo', 'fas fa-percent', Message::class);

        yield MenuItem::section('Mode');
        yield MenuItem::linkToRoute('Passer en mode Client', 'fas fa-user-tie', 'app_user_page');
        yield MenuItem::linkToRoute('Accueil du site', 'fas fa-globe', 'app_accueil');
    }
}
