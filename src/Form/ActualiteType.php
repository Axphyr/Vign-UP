<?php

namespace App\Form;

use App\Entity\Actualite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, ['attr' => ['placeholder' => "Saisir le type de l'actualité."], 'label' => 'Type'])
            ->add('intitule', TextType::class, ['attr' => ['placeholder' => "Saisir l'intitulé de l'actualité."], 'label' => 'Intitulé'])
            ->add('description', TextareaType::class, ['attr' => ['placeholder' => "Saisir la description de l'actualité."], 'label' => 'Description'])
            ->add('cover', FileType::class, ['required' => false, 'label' => 'Image', 'data_class' => File::class, 'attr' => ['accept' => 'image/jpeg,image/png']])
        ;

        $builder->get('cover')
            ->addModelTransformer(new CallbackTransformer(
                function () {
                    return null;
                },
                function ($cover) {
                    return is_null($cover) ? null : file_get_contents($cover);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}
