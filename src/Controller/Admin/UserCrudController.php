<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // Champs principaux
            TextField::new('entreprise', 'Entreprise'),
            EmailField::new('email', 'Email'),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(),
            DateTimeField::new('createdAt', 'Date de création')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->hideOnForm(),

            // Champs pour les nouvelles informations
            TextField::new('adresse', 'Adresse')->setMaxLength(255),
            TextField::new('nif', 'NIF')->setMaxLength(50),
            TextField::new('stat', 'STAT')->setMaxLength(50),
            TextField::new('cif', 'CIF')->setMaxLength(50),
            TextField::new('rcs', 'RCS')->setMaxLength(50),
            TextField::new('rc', 'RC')->setMaxLength(50),

            // Champ de mot de passe (idéalement caché)
            TextField::new('password', 'Mot de passe')
                ->onlyOnForms(), // Caché dans les listes, visible uniquement dans les formulaires
        ];
    }
}
