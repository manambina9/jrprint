<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PromotionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Promotion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),  
            NumberField::new('discountPercentage', 'Pourcentage de réduction')->setNumDecimals(2),
            NumberField::new('discountedPrice', 'Prix réduit')->setNumDecimals(2),
            DateTimeField::new('promotionStart', 'Début de la promotion'),
            DateTimeField::new('promotionEnd', 'Fin de la promotion'),
            AssociationField::new('prestation', 'Prestation associée'),
        ];
    }
}
