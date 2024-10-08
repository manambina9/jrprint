<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), 
            TextField::new('name')->setLabel('Nom'),
            TextField::new('email')->setLabel('E-mail'),
            TextField::new('subject')->setLabel('Objet'),
            TextEditorField::new('message')->setLabel('Message'),
            DateTimeField::new('createdAt')->setLabel('Date de création')->setFormat('dd-MM-yyyy HH:mm')->hideOnForm(), 
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Message) {
            return;
        }

        $entityInstance->setCreatedAt(new \DateTimeImmutable());

        parent::persistEntity($entityManager, $entityInstance);
    }
}
