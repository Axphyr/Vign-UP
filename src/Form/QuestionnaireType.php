<?php

namespace App\Form;

use App\Entity\Conseil;
use App\Entity\Questionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $max = null;
        $partieConnecteValue = 0;
        $imagePresentationQ = null;
        if (isset($options['data']) && $options['data'] instanceof Questionnaire) {
            $max = count($options['data']->getQuestions());
            $imagePresentationQ = $options['data']->getImagePresentation();
            if (is_null($options['data']->getPartieConnecte()) || 0 == $options['data']->getPartieConnecte()) {
                $visibility = 'hidden';
            } else {
                $partieConnecteValue = $options['data']->getPartieConnecte();
                $visibility = 'initial';
            }
        } else {
            $visibility = 'hidden';
        }
        $builder
            ->add('partieConnecte', IntegerType::class, [
                'empty_data' => null,
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => $max,
                    'onChange' => 'onChangePartieConnecte()',
                    'value' => $partieConnecteValue,
                ],
                'label' => 'Question connecte',
            ])
            ->add('Nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez ici le nom du questionnaire...',
                ],
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => null,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez ici la description du questionnaire...',
                ],
            ])
            ->add('imagePresentation', FileType::class, [
                'data_class' => File::class,
                'empty_data' => null,
                'required' => false,
                'attr' => ['accept' => 'image/jpeg,image/png'],
            ])
            ->add('roleConnecte', ChoiceType::class, [
                'empty_data' => ['ROLE_USER'],
                'required' => false,
                'choices' => [
                    'Tous utilisateurs connecté' => 'ROLE_USER',
                    'Prestataire' => 'ROLE_PRESTATAIRE',
                    'Vigneron' => 'ROLE_VIGNERON',
                    'Fournisseur' => 'ROLE_FOURNISSEUR',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'onChange' => 'onChangeRoleConnecte()',
                ],
                'row_attr' => [
                    'class' => 'role_connecte',
                    'style' => "visibility: {$visibility};",
                ],
            ])
            ->add('Questions', CollectionType::class, [
                'entry_type' => QuestionType::class,
                'label' => 'Les questions de ce questionnaire :',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => [
                    'label' => 'Question numéro',
                    'row_attr' => [
                        'class' => 'questionnaire_question',
                    ],
                ],
                'row_attr' => [
                    'class' => 'questionnaire_questions',
                ],
            ])
            ->add('Conseils', CollectionType::class, [
                'entry_type' => ConseilType::class,
                'label' => 'Les conseils pour ce questionnaire :',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => [
                    'attr_translation_parameters' => [
                        'visibility' => $visibility,
                    ],
                    'label' => 'Conseil n°',
                    'row_attr' => [
                        'class' => 'questionnaire_conseil',
                    ],
                ],
                'row_attr' => [
                    'class' => 'questionnaire_conseils',
                ],
            ])
        ;

        $builder->get('imagePresentation')
            ->addModelTransformer(new CallbackTransformer(
                function () {
                    return null;
                },
                function ($imagePresentation) use ($imagePresentationQ) {
                    return is_null($imagePresentation) ? $imagePresentationQ : file_get_contents($imagePresentation);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view['Questions']->children as $childView) {
            $numero = $childView->vars['value']->getNumero();
            $childView->vars['label'] = "Question numéro {$numero}";
        }
        $numero = 0;
        foreach ($view['Conseils']->children as $childView) {
            ++$numero;
            $childView->vars['label'] = "Conseil n°{$numero}";
            if ($childView->vars['value'] instanceof Conseil) {
                if (!is_null($childView->vars['value']->isPartieConnecte()) && !$childView->vars['value']->isPartieConnecte()) {
                    $childView->children['categorieQuestion']->vars['attr'] = [
                        'style' => 'visibility: hidden',
                    ];
                    $childView->children['categorieQuestion']->vars['label_attr'] = [
                        'style' => 'visibility: hidden',
                    ];
                } else {
                    $childView->children['categorieQuestion']->vars['attr'] = [
                        'style' => 'visibility: initial',
                    ];
                    $childView->children['categorieQuestion']->vars['label_attr'] = [
                        'style' => 'visibility: initial',
                    ];
                }
            }
        }
    }
}
