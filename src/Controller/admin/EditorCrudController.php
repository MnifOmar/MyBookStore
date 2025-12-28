<?php

// ============================================
// src/Controller/Admin/EditorCrudController.php
// ============================================

namespace App\Controller\Admin;

use App\Entity\Editor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use Doctrine\ORM\EntityManagerInterface;

class EditorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Editor::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            TextField::new('address', 'Adresse')->hideOnIndex(),
            TextField::new('phone', 'Téléphone')->hideOnIndex(),
            EmailField::new('email', 'Email'),
        ];
    }
    public function deleteEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof Editor) {
            if ($entityInstance->getBooks()->count() > 0) {
                $this->addFlash('error', 'Impossible de supprimer cet éditeur car il a des livres associés.');
                return;
            }
        }

        parent::deleteEntity($em, $entityInstance);
    }
}
