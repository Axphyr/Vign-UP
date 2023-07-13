<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remove', ButtonType::class, [
                'attr' => [
                    'onClick' => 'removeItem(event)',
                    'class' => 'button_remove',
                ],
                'label' => 'Suprimer cette réponse',
                'row_attr' => [
                    'class' => 'button_remove_div',
                ],
            ])
            ->add('txtReponse', TextType::class, [
                'label' => 'Texte de la réponse',
            ])
            ->add('nombrePoints', IntegerType::class, [
                'attr' => [
                    'min' => 0,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
