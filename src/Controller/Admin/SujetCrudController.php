<?php

namespace App\Controller\Admin;

use App\Entity\Sujet;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SujetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sujet::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('titre'),
            DateTimeField::new('dateCreation'),
            AssociationField::new('user')->setFormTypeOptions(
                [
                    'class' => User::class,
                    'choice_label' => 'pseudo'
                ]
            )->formatValue(function ($value, $entity) {
                return is_null($value) ? 'Null' :
                    $entity->getUser()->getPseudo();
            }),
        ];
    }
}
