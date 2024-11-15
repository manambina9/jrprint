<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
        // Définition des choix de catégories
        $categoryChoices = [
            'Panneau' => 'Panneau',
            'Autres Services' => 'Autres Services',
        ];

        // Titres pour la catégorie Panneau
        $panneauTitles = [
            'Panneau sucette – Format 2*1',
            'Panneau – Format 4x3',
            'Panneau – Format 8x3',
            'Panneau – Format 12x3',
            'Panneau – Format 9x3',
            'Panneau – Format 6x3',
        ];

        // Titres pour la catégorie Autres Services
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

        // Champs de catégorie et de titre
        $categoryField = ChoiceField::new('category', 'Catégorie')
            ->setChoices($categoryChoices)
            ->setRequired(true);

        $titleField = ChoiceField::new('name', 'Titre')
            ->setChoices(array_merge(
                array_combine($panneauTitles, $panneauTitles),
                array_combine($autresServicesTitles, $autresServicesTitles)
            ))
            ->setRequired(true);

            return [
                IdField::new('id')->hideOnForm(),
                
                $categoryField,
                $titleField,
                
                TextEditorField::new('description', 'Description')
                    ->setRequired(true)
                    ->setHelp('Entrez une description complète de la prestation'),
                
                ArrayField::new('advantages', 'Avantages')
                    ->setHelp('Liste des avantages sous forme de tableau JSON'),
                
                ArrayField::new('characteristics', 'Caractéristiques')
                    ->setHelp('Liste des caractéristiques sous forme de tableau JSON'),
                
                ArrayField::new('images3d', 'Images 3D')
                    ->setHelp('Images 3D sous forme de tableau JSON'),
                
                ArrayField::new('locations', 'Localisation')
                    ->setHelp('Obligatoire pour les panneaux'),
                
                BooleanField::new('available', 'Disponible'),
                
                TextField::new('imageFile', 'Nouvelle Image')
                    ->setFormType(VichImageType::class)
                    ->onlyOnForms()
                    ->setHelp('Téléchargez une nouvelle image pour la prestation'),
                
                ImageField::new('imageUrl', 'Image')
                    ->setBasePath('/images/prestations')
                    ->onlyOnIndex()
                    ->setHelp('Image actuelle de la prestation'),
                
                DateTimeField::new('createdAt', 'Créé le')
                    ->hideOnForm()
                    ->setHelp('Date de création de la prestation'),
                
                DateTimeField::new('updatedAt', 'Mis à jour le')
                    ->hideOnIndex()
                    ->setHelp('Dernière mise à jour de la prestation')
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