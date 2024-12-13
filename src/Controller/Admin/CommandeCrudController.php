<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('dateCommande')
                ->setLabel('Date de Commande')
                ->hideOnIndex(),
            DateTimeField::new('dateDebutLocation')
                ->setLabel('Date de Début de Location'),
            DateTimeField::new('dateFinLocation')
                ->setLabel('Date de Fin de Location'),
            MoneyField::new('montantTotal')
                ->setLabel('Montant Total')
                ->setCurrency('MGA'),
            ChoiceField::new('statut')
                ->setLabel('Statut')
                ->setChoices([
                    'En attente' => 'en attente',
                    'En cours' => 'en cours',
                    'Livrée' => 'livrée',
                    'Annulée' => 'annulée',
                ]),
            AssociationField::new('client')->setLabel('Client'),
            AssociationField::new('prestations')
                ->setLabel('Prestations')
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('by_reference', false)
                ->autocomplete(),
            TextEditorField::new('detailCommande')
                ->setLabel('Détails de la commande'),
        ];
    }
}
