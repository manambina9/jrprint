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
            SELECT SUM(f.montant_total) AS total_revenus
            FROM facture f
        ';

        $stmt = $conn->executeQuery($sql);

        return (float) $stmt->fetchOne();
    }
}
