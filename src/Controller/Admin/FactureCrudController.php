<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use Doctrine\ORM\EntityManagerInterface;

class FactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('numero', 'Numéro de facture'),
            DateField::new('date', 'Date de facture'),
            AssociationField::new('client', 'Client'),
            AssociationField::new('prestations', 'Prestations')
                ->setFormTypeOptions(['by_reference' => false])
                ->onlyOnForms(),
            MoneyField::new('montantTotal', 'Montant Total')
                ->setCurrency('MGA')
                ->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Facture) {
            $entityInstance->calculerMontantTotal();
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Facture) {
            $entityInstance->calculerMontantTotal();
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
