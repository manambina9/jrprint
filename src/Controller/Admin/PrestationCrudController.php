<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('description', 'Description'),
            MoneyField::new('price', 'Prix')->setCurrency('MGA'),
            ChoiceField::new('category', 'Catégorie')
                ->setChoices([
                    'Panneau 12*3' => 'panneau_12_3',
                    'Panneau 8*3' => 'panneau_8_3',
                    'Panneau 6*3' => 'panneau_6_3',
                    'Panneau 4*3' => 'panneau_4_3',
                    'Habillage véhicules' => 'habillage_vehicules',
                    'Photobooth' => 'photobooth',
                    'Bâches tendue' => 'baches_tendue',
                ]),
            BooleanField::new('available', 'Disponible'),

            // Champ pour uploader une nouvelle image
            TextField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(), // Masquer dans l'index

            // Champ pour afficher l'image actuelle
            ImageField::new('imageUrl', 'Image actuelle')
                ->setBasePath('/images/prestations') // Correspond à uri_prefix
                ->setUploadDir('public/images/prestations') // Spécifiez le répertoire de téléchargement
                ->onlyOnForms(), // Afficher uniquement dans le formulaire

            IntegerField::new('quantityAvailable', 'Quantité disponible'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnForm(),

            AssociationField::new('promotions', 'Promotions')->hideOnIndex(),
        ];
    }
}
