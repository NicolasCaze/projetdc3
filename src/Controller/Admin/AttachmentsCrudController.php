<?php

namespace App\Controller\Admin;

use App\Entity\Attachments;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Field\VichImageField;

class AttachmentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Attachments::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('filename')->hideOnIndex(),
            TextField::new('mime_type'),
            TextEditorField::new('alt_text'),
            IntegerField::new(propertyName: 'size')->hideOnForm(),
            VichImageField::new('file'),
    ];
    }
    
}
