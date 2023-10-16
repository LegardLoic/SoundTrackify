<?php

namespace App\Form\Front;

use App\Entity\User;
use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamerTag', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'GamerTag',
                'label_attr' => [
                    'class' => 'form-label mt-4 user-form__title'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label mt-4 user-form__title'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                ]
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'attr' => [
                            'class' => 'form-control'
                        ],
                        'label' => 'Mot de passe',
                        'label_attr' => [
                            'class' => 'form-label mt-4 user-form__title'
                        ]
                    ],
                    'second_options' => [
                        'attr' => [
                            'class' => 'form-control'
                        ],
                        'label' => 'Confirmation du mot de passe',
                        'label_attr' => [
                            'class' => 'form-label mt-4 user-form__title'
                        ]
                    ],
                    'invalid_message' => 'Les mots de passe ne correspondent pas.'
                ])
            ->add('avatar', EntityType::class, [
                'label' => 'Choisir un avatar',
                'class' => Avatar::class,
                'choice_label' => 'poster',
                'multiple' => false,
                'expanded' => true,
                'label_attr' => [
                    'class' => 'user-form__title'
                ],
                'attr' => [
                    'class' => 'user-form__avatar'
                ],

            ])
            ->add('Modifier', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary btn user-form__button mt-4'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
