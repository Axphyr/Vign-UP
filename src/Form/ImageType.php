<?php

namespace App\Form;

use App\Entity\Actualite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cover', FileType::class, ['required' => false, 'label' => false, 'data_class' => File::class, 'attr' => ['accept' => 'image/jpeg,image/png']])
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
