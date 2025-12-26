<?php
// ============================================
// src/Controller/Admin/OrderCrudController.php
// ============================================

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('orderNumber', 'Numéro')->hideOnForm(),
            AssociationField::new('user', 'Client'),
            MoneyField::new('totalAmount', 'Montant')->setCurrency('EUR'),
            ChoiceField::new('status', 'Statut')->setChoices([
                'En attente' => 'pending',
                'En traitement' => 'processing',
                'Expédiée' => 'shipped',
                'Livrée' => 'delivered',
                'Annulée' => 'cancelled',
            ]),
            DateTimeField::new('createdAt', 'Date')->hideOnForm(),
        ];
    }
}
