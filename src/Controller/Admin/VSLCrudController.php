<?php

namespace App\Controller\Admin;

use App\Entity\VSL;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class VSLCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VSL::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            NumberField::new('superficie'),
            TextField::new('description'),

        ];
    }
}
