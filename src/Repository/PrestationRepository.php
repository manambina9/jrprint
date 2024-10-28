<?php
// src/Repository/PrestationRepository.php

namespace App\Repository;

use App\Entity\Prestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection; 

class PrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prestation::class);
    }

    public function countServicesAutresQuePanneau(): int
    {
        $qb = $this->createQueryBuilder('p');
        
        // Utilisez le QueryBuilder pour construire votre requête
        $qb->select('COUNT(p.id)')
            ->where('p.category != :categoryPanneau')
            ->setParameter('categoryPanneau', 'panneau'); // Remplacez 'panneau' par la valeur réelle
    
        // Exécutez la requête et récupérez le résultat
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }
    
    
}
