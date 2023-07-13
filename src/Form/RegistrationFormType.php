<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'user-prenom',
                ],
                'required' => true])
            ->add('lastname', TextType::class, [
                'label' => 'Nom de famille',
                'row_attr' => [
                    'class' => 'user-nom',
                ],
                'required' => true])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'row_attr' => [
                    'class' => 'user-email',
                ],
                'required' => true])
            ->add('roles', ChoiceType::class, [
                'empty_data' => ['ROLE_USER'],
                'required' => false,
                    'row_attr' => [
                        'class' => 'user-roles',
                    ],
                'choices' => [
                    'Prestataire' => 'ROLE_PRESTATAIRE',
                    'Vigneron' => 'ROLE_VIGNERON',
                    'Fournisseur' => 'ROLE_FOURNISSEUR',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => "Type d'utilisateur",
                ]
            )
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "Conditions d'utilisation",
                'mapped' => false,
                'row_attr' => [
                    'class' => 'user-terms',
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuilliez accepter les conditions générale d'utilisation",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'row_attr' => [
                    'class' => 'user-pass',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => "Votre mot de passe doit être d'une longueure de {{ limit }} caractères minimum",
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudonyme',
                'row_attr' => [
                    'class' => 'user-pseudo',
                ],
                'required' => true])
            ->add('telUser', TextType::class, [
                'label' => 'Numéro de téléphone',
                'row_attr' => [
                    'class' => 'user-tel',
                ],
                'required' => true])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'row_attr' => [
                    'class' => 'user-address',
                ],
                'required' => true])
            ->add('cp', TextType::class, [
                'label' => 'Code Postal',
                'row_attr' => [
                    'class' => 'user-cp',
                ],
                'required' => true])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
