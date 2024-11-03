<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationCrudController extends AbstractCrudController
{
    private PrestationRepository $prestationRepository;

    // Injecter le repository via le constructeur
    public function __construct(PrestationRepository $prestationRepository)
    {
        $this->prestationRepository = $prestationRepository;
    }

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
                    'Panneau 12*3' => 'Panneau 12*3',
                    'Panneau 8*3' => 'Panneau 8*3',
                    'Panneau 6*3' => 'Panneau 6*3',
                    'Panneau 4*3' => 'Panneau 4*3',
                    'Habillage véhicules' => 'Habillage véhicules',
                    'Photobooth' => 'Photobooth',
                    'Bâches tendue' => 'Bâches tendue',
                    // Ajoutez d'autres catégories si nécessaire
                ]),
            BooleanField::new('available', 'Disponible'),
            TextField::new('imageFile', 'Nouvelle Image')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('imageUrl', 'Image')
                ->setBasePath('/images/prestations')
                ->onlyOnIndex(),
            IntegerField::new('quantityAvailable', 'Qt disponible'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnForm(),
            AssociationField::new('promotions', 'Promotions')->hideOnIndex(),
        ];
    }

    #[Route('/prestations/category/{category}', name: 'app_show_prestations_by_category')]
    public function showByCategory(string $category): Response
    {
        $prestations = $this->prestationRepository->findBy(['category' => $category]);

        return $this->render('prestations/show.html.twig', [
            'prestations' => $prestations,
            'category' => $category,
        ]);
    }
}