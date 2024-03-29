<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new(propertyName: 'id')->hideOnForm(),
            EmailField::new(propertyName: 'email'),
            TextField::new(propertyName:'firstname'),
            TextField::new(propertyName:'lastname'),
            TextField::new(propertyName:'phone'),
            ChoiceField::new(propertyName: 'roles')
            ->setChoices([
                'Super admin' => 'ROLE_SUPER_ADMIN',
                'Administrateur' => 'ROLE_ADMIN',
            ])
            ->setRequired(isRequired: false)
            ->allowMultipleChoices(allow: true),
            TextField::new(propertyName: 'plainPassword')->onlyOnForms(),
        ];
    }
    
}
