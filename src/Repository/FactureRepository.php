<?php

// src/Repository/FactureRepository.php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    public function getTotalRevenus(): float
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT ROUND(SUM(f.montant_total) / 100, 2) AS total_revenus FROM facture f
        ';

        $stmt = $conn->executeQuery($sql);

        return (float) $stmt->fetchOne();
    }

    public function countRevenuByMonth(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT YEAR(f.date) AS year, MONTH(f.date) AS month, ROUND(SUM(f.montant_total) / 100, 2) AS total
        FROM facture f
        GROUP BY year, month
        ORDER BY year ASC, month ASC

        ';

        $stmt = $conn->executeQuery($sql);

        return $stmt->fetchAllAssociative(); 
    }
}
