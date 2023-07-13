<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Entity\Reponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reponse::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('txtReponse'),
            AssociationField::new('question')->setFormTypeOptions(
                [
                    'class' => Question::class,
                    'choice_label' => 'txtQuestion'
                ]
            )->formatValue(function ($value, $entity) {
                return is_null($value) ? 'Null' :
                    $entity->getQuestion()->getTxtQuestion();
            }),
        ];
    }
}
