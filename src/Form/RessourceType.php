<?php

namespace App\Form;

use App\Entity\Ressource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeRessource', TextType::class, ['attr' => ['placeholder' => 'Saisir le type de la ressource.'], 'label' => 'Type'])
            ->add('nomRessource', TextType::class, ['attr' => ['placeholder' => 'Saisir le nom de la ressource.'], 'label' => 'Nom'])
            ->add('descriptif', TextareaType::class, ['attr' => ['placeholder' => 'Saisir la description de la ressource.'], 'label' => 'Description'])
            ->add('url', TextType::class, ['attr' => ['placeholder' => "Saisir l'url de la ressource."], 'label' => 'URL'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
