<?php

namespace App\Controller\Admin;

use App\Entity\CategorieQuestion;
use App\Entity\Conseil;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConseilCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conseil::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('txtConseil'),
            IntegerField::new('noteMinimale'),
            AssociationField::new('categorieQuestion')->setFormTypeOptions(
                [
                    'class' => CategorieQuestion::class,
                    'choice_label' => 'nom'
                ]
            )->formatValue(function ($value, $entity) {
                return is_null($value) ? 'Null' :
                    $entity->getCategorieQuestion()->getNom();
            }),
        ];
    }
}
