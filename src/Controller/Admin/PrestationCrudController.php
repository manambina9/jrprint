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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrestationCrudController extends AbstractCrudController
{
    private PrestationRepository $prestationRepository;

    // Injection du repository via le constructeur
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
        $categoryChoices = [
            'Panneau' => 'Panneau',
            'Autres Services' => 'Autres Services',
        ];

        $panneauTitles = [
            'Panneau sucette – Format 2*1',
            'Panneau – Format 4x3',
            'Panneau – Format 8x3',
            'Panneau – Format 12x3',
            'Panneau – Format 9x3',
            'Panneau – Format 6x3',
        ];

        $autresServicesTitles = [
            'Habillages cuves & Transtack',
            'Décorations évènementielles',
            'Silhouettes',
            'Habillage véhicules',
            'Décorations Plaque sur PVC',
            'Photobooth',
            'Habillages vitrines / Vitrophanie',
            'Branding 3',
            'Photocall',
            'Bâche tendue',
            'Stop trottoir',
            'Habillages comptoirs',
            'Totem',
            'Habillages boutiques',
        ];

        $categoryField = ChoiceField::new('category', 'Catégorie')
            ->setChoices($categoryChoices)
            ->setRequired(true);

        $titleField = ChoiceField::new('title', 'Titre')
            ->setChoices(array_combine($panneauTitles, $panneauTitles) + array_combine($autresServicesTitles, $autresServicesTitles))
            ->setRequired(true);
        

        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            $categoryField,
            $titleField,
            TextEditorField::new('description', 'Description')->setRequired(true),
            MoneyField::new('price', 'Prix')->setCurrency('MGA')->setRequired(true),
            TextField::new('location', 'Localisation')->setHelp('Obligatoire pour les panneaux'),
            BooleanField::new('available', 'Disponible'),
            TextField::new('imageFile', 'Nouvelle Image')->setFormType(VichImageType::class)->onlyOnForms(),
            ImageField::new('imageUrl', 'Image')->setBasePath('/images/prestations')->onlyOnIndex(),
            IntegerField::new('quantityAvailable', 'Qt disponible'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->hideOnIndex(),
            AssociationField::new('promotions', 'Promotions')->setRequired(false),
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
