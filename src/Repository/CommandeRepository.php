<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeImmutable;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findCommandesProchesEcheance(DateTimeImmutable $dateLimite)
    {
        return $this->createQueryBuilder('c')
            ->where('c.dateFinLocation <= :dateLimite')
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
    
    

    public function calculateTotalRevenue(): float
    {
        return $this->createQueryBuilder('c')
            ->select('SUM(c.montantTotal)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
