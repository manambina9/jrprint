<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Message;
use App\Entity\Commande;
use App\Entity\Promotion;
use App\Entity\Prestation;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use App\Repository\FactureRepository; 
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class TableauBordController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/admin', name: 'admin')]
    public function dashboard(
        UserRepository $userRepository,
        CommandeRepository $commandeRepository,
        FactureRepository $factureRepository
    ): Response {

        $usersByMonth = $userRepository->countUsersByMonth() ;
        $revenueByMonth = $factureRepository->countRevenuByMonth();
        $revenue = $factureRepository->getTotalRevenus();  
        $totalUsers = $userRepository->countUsers();
        $revenusGeneres = $factureRepository->getTotalRevenus();
        $sales = $factureRepository->count([]);
        $panneauxDisponibles = $commandeRepository->countPanneauxDisponibles();
        dump($panneauxDisponibles); 
        $nombreServicesAutresQuePanneau = $commandeRepository->countServicesAutresQuePanneau();
        $nombreServicesPanneau = $commandeRepository->countPanneauxLoue();
 
        $customers = $userRepository->count([]);
        $panneauxLouesParMois = $commandeRepository->countPanneauxLouesParMois();
        return $this->render('admin/dashboard.html.twig', [
            'revenusGeneres' => $revenusGeneres,
            'totalUsers' => $totalUsers,
            'usersByMonth' => $usersByMonth,
            'revenueByMonth' => $revenueByMonth,
            'sales' => $sales,
            'panneauxDisponibles' => $panneauxDisponibles,
            'customers' => $customers,
            'revenue' => $revenue,
            'nombre_services_autres_que_panneau' => $nombreServicesAutresQuePanneau,
            'nombrePanneauLoue' => $nombreServicesPanneau,
            'panneauxLouesByMonth' => $panneauxLouesParMois,
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
        yield MenuItem::section('Mode');
        yield MenuItem::linkToRoute('Passer en mode Client', 'fas fa-user-tie', 'user');
        yield MenuItem::linkToRoute('Accueil du site', 'fas fa-globe', 'app_accueil');
    }
}
