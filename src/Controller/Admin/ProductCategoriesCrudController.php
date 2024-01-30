<?php

namespace App\Controller\Admin;

use App\Entity\ProductCategories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductCategories::class;
    }

    public function configureFields(string $pageName): iterable 
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            IdField::new(propertyName: 'name'),
            IdField::new(propertyName: 'description'),
            AssociationField::new(propertyName:'thumbnail_id'),
            
        ];
    }

}
