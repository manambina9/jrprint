<?php

namespace App\Repository;

use DateTimeImmutable;
use App\Entity\Commande;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findCommandesAvecEcheanceProche(DateTimeImmutable $dateLimite): array
    {
        $aujourdhui = new DateTimeImmutable();

        return $this->createQueryBuilder('c')
            ->where('c.dateFinLocation BETWEEN :aujourdhui AND :dateLimite')
            ->setParameter('aujourdhui', $aujourdhui)
            ->setParameter('dateLimite', $dateLimite)
            ->getQuery()
            ->getResult();
    } 

    public function countPanneauxDisponibles(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        
        // Exécutez la requête pour compter les panneaux disponibles
        $sql = 'SELECT COUNT(*) FROM prestation WHERE available = 1';
        $stmt = $conn->executeQuery($sql);
        
        return (int) $stmt->fetchOne(); // Retourne le résultat comme un entier
    }

    public function countPanneauxLouesParMois(string $statut = 'livrée'): array
    {
        // Récupérer toutes les commandes avec le statut donné
        $qb = $this->createQueryBuilder('c')
            ->select('c.dateCommande')
            ->where('c.statut = :statut')
            ->setParameter('statut', $statut)
            ->getQuery();
    
        $commandes = $qb->getArrayResult();
    
        // Initialiser un tableau pour compter les panneaux loués par mois
        $panneauxParMois = [];
    
        foreach ($commandes as $commande) {
            $date = $commande['dateCommande'];
            
            if ($date) {
                $month = (int) $date->format('m');
                $year = (int) $date->format('Y');  
                
                $key = sprintf('%d-%02d', $year, $month);
                
                if (!isset($panneauxParMois[$key])) {
                    $panneauxParMois[$key] = 0;
                }
                $panneauxParMois[$key]++;
            }
        }
    
        $result = [];
        foreach ($panneauxParMois as $key => $count) {
            list($year, $month) = explode('-', $key);
            $result[] = [
                'month' => (int)$month,
                'count' => $count,
            ];
        }
    
        return $result;
    }

    public function calculateTotalRevenue(): float
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.montantTotal)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
