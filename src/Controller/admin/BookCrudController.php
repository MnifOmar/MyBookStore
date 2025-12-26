<?php
// ============================================
// src/Controller/Admin/BookCrudController.php
// ============================================

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextField::new('isbn', 'ISBN'),
            TextareaField::new('description', 'Description')->hideOnIndex(),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            IntegerField::new('stock', 'Stock'),
            IntegerField::new('publicationYear', 'Année'),
            AssociationField::new('category', 'Catégorie'),
            AssociationField::new('editor', 'Éditeur'),
            AssociationField::new('authors', 'Auteurs')->hideOnIndex(),
            ImageField::new('coverImage', 'Image')
                ->setBasePath('uploads/books')
                ->setUploadDir('public/uploads/books')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
}


