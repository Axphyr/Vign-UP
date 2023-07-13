<?php

namespace App\Form;

use App\Entity\Sujet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SujetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['attr' => ['placeholder' => 'Saisir le titre du sujet.']])
            ->add('messages', CollectionType::class, ['entry_type' => MessageType::class, 'entry_options' => ['label' => false]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sujet::class,
        ]);
    }
}
