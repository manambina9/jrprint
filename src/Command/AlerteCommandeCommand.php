<?php 
 
namespace App\Command;

use DateTimeImmutable;
use App\Services\AlerteEmailService;
use App\Repository\CommandeRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AlerteCommandeCommand extends Command
{
    protected static $defaultName = 'app:alerte-location';

    private $commandeRepository;
    private $alerteEmailService;

    public function __construct(CommandeRepository $commandeRepository, AlerteEmailService $alerteEmailService)
    {
        parent::__construct();
        $this->commandeRepository = $commandeRepository;
        $this->alerteEmailService = $alerteEmailService;
    }

    protected function configure()
    {
        $this
            ->setName('app:alerte-location')
            ->setDescription("Envoie une alerte par email lorsque la date de fin de location est dans 10 jours.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dateLimite = (new DateTimeImmutable())->modify('+10 days');
        $output->writeln("Date limite fixée à : " . $dateLimite->format('Y-m-d H:i:s'));
    
        $commandes = $this->commandeRepository->findCommandesAvecEcheanceProche($dateLimite);
        
        foreach ($commandes as $commande) {
            $this->alerteEmailService->envoyerAlerte('contact@entreprise.com', $commande);
            $output->writeln("Alerte envoyée pour la commande #" . $commande->getId());
        }
    
        if (count($commandes) === 0) {
            $output->writeln("Aucune commande trouvée avec une échéance dans 10 jours.");
        }
    
        return Command::SUCCESS;
    }
    

}
