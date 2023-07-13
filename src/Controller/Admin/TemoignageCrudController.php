<?php

namespace App\Controller\Admin;

use App\Entity\Temoignage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TemoignageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Temoignage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('contenuTemoignage'),
        ];
    }
}
