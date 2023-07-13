<?php

namespace App\Form;

use App\Entity\CategorieQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'row_attr' => [
                    'class' => 'input',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieQuestion::class,
        ]);
    }
}
