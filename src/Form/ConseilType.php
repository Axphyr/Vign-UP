<?php

namespace App\Form;

use App\Entity\CategorieQuestion;
use App\Entity\Conseil;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConseilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remove', ButtonType::class, [
                'attr' => [
                    'onClick' => 'removeItem(event)',
                    'class' => 'button_remove',
                ],
                'label' => 'Suprimer ce conseil',
                'row_attr' => [
                    'class' => 'button_remove_div',
                ],
            ])
            ->add('txtConseil', TextareaType::class, [
                'label' => 'Le message du conseil',
                'attr' => [
                    'onChange' => 'onChangeConseil(event)',
                ],
            ])
            ->add('noteMinimale', IntegerType::class, [
                'empty_data' => 0,
                'attr' => [
                    'min' => 0,
                    'onChange' => 'onChangeConseil(event)',
                ],
                'label' => "La note minimale pour que ce conseil s'affiche",
            ])
            ->add('partieConnecte', CheckboxType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'conseil_partieConnecte',
                    'style' => "visibility: {$options['attr_translation_parameters']['visibility']};",
                ],
                'attr' => [
                    'onChange' => 'onChangeConseilPartieConnecte(event)',
                ],
                'label' => "Le conseil s'applique-t-il sur la partie connecte ?",
            ])
            ->add('categorieQuestion', EntityType::class, [
                'class' => CategorieQuestion::class,
                'required' => false,
                'placeholder' => 'global',
                'choice_label' => 'Nom',
                'label' => 'Le conseil porte sur la catégorie :',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->orderBy('c.Nom', 'ASC');
                },
                'row_attr' => [
                    'class' => 'conseil_categorieQuestion',
                ],
                'attr' => [
                    'onChange' => 'onChangeConseil(event)',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conseil::class,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
    }
}
