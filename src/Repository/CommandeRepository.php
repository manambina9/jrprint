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
}
