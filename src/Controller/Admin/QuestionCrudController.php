<?php

namespace App\Controller\Admin;

use App\Entity\CategorieQuestion;
use App\Entity\Question;
use App\Entity\Questionnaire;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('txtQuestion'),
            AssociationField::new('questionnaire')->setFormTypeOptions(
                [
                    'class' => Questionnaire::class,
                    'choice_label' => 'id',
                ]
            )->formatValue(function ($value, $entity) {
                return is_null($value) ? 'Null' :
                    $entity->getQuestionnaire()->getId();
            }),

            AssociationField::new('categorieQuestion')->setFormTypeOptions(
                [
                    'class' => CategorieQuestion::class,
                    'choice_label' => 'nom',
                ]
            )->formatValue(function ($value, $entity) {
                return is_null($value) ? 'Null' :
                    $entity->getCategorieQuestion()->getNom();
            }),
        ];
    }
}
