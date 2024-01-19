<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName:'id')->hideOnForm(),
            TextField::new(propertyName:'name'),
            TextEditorField::new(propertyName:'description'),
            MoneyField::new(propertyName:'price')->setCurrency('EUR'),
            AssociationField::new(propertyName:'category_id'),
            AssociationField::new(propertyName:'thumbnail_id'),
            BooleanField::new(propertyName:'available'),
        ];
    }
    
}
