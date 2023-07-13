<?php

namespace App\Form;

use App\Entity\CategorieQuestion;
use App\Entity\Question;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', HiddenType::class)
            ->add('remove', ButtonType::class, [
                'attr' => [
                    'onClick' => 'removeItem(event)',
                    'class' => 'button_remove',
                ],
                'label' => 'Suprimer cette question',
                'row_attr' => [
                    'class' => 'button_remove_div',
                ],
            ])
            ->add('txtQuestion')
            ->add('categorieQuestion', EntityType::class, [
                'class' => CategorieQuestion::class,
                'required' => false,
                'placeholder' => 'Pas de catégorie',
                'choice_label' => 'Nom',
                'label' => 'Catégorie de la question',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->orderBy('c.Nom', 'ASC');
                },
            ])
            ->add('Add', ButtonType::class, [
                'attr' => [
                    'onClick' => 'clickAddItem(event)',
                    'class' => 'button_add',
                ],
                'label' => 'Ajouter une réponse',
            ])
            ->add('Reponses', CollectionType::class, [
                'entry_type' => ReponseType::class,
                'label' => 'Les réponses associées à cette question :',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'prototype_name' => '__name2__',
                'by_reference' => false,
                'entry_options' => [
                    'label' => 'Réponse n°',
                    'row_attr' => [
                        'class' => 'questionnaire_reponse',
                    ],
                ],
                'row_attr' => [
                    'class' => 'question_reponses',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $numero = 0;
        foreach ($view['Reponses']->children as $childView) {
            ++$numero;
            $childView->vars['label'] = "Réponse n°{$numero}";
        }
    }
}
