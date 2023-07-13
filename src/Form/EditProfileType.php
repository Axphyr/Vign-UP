<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Adresse mail',
                'row_attr' => [
                    'class' => 'user-mail',
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'row_attr' => [
                    'class' => 'user-pseudo',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'user-firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'user-lastname',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Biographie',
                'row_attr' => [
                    'class' => 'user-desc',
                ],
            ])
            ->add('avatar', FileType::class, [
                'data_class' => File::class,
                'empty_data' => null,
                'required' => false,
                'label' => 'Photo de profile',
                'attr' => ['accept' => 'image/jpeg,image/png'],
                'row_attr' => [
                    'class' => 'user-avatar',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse professionnelle',
                'row_attr' => [
                    'class' => 'user-adress',
                ],
            ])
            ->add('cp', TextType::class, [
                'label' => 'Code Postal',
                'row_attr' => [
                    'class' => 'user-cp',
                ],
            ])
            ->add('telUser', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'row_attr' => [
                    'class' => 'user-tel',
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'row_attr' => [
                    'class' => 'submit-edit-profile',
                ],
            ])
        ;

        $builder->get('avatar')
            ->addModelTransformer(new CallbackTransformer(
                function () {
                    return null;
                },
                function ($avatar) {
                    return is_null($avatar) ? file_get_contents('img/default_avatar.jpg') : file_get_contents($avatar);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
