<?php

namespace App\Form\Back;

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gamerTag', TextType::class, [
                'attr' => [
                    'placeholder' => '',
                ],
                'empty_data' => "",
                'label' => 'GamerTag',
                'help' => 'Pseudonyme (celui ci doit être unique)',
            ])
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'label' => 'roles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                // checkboxes
                'expanded' => true,
            ])
            // Below we place an event listener on the form
            // As soon as the PRE_SET_DATA event is triggered, the 2nd parameter callback function is executed.
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                // Here I'm storing all the form data in $form, and retrieving it from the event (to work with).
                $form = $event->getForm();
                // We want to retrieve the user mapped to the form from the even
                $user = $event->getData();
                // Here I check whether the user exists or not
                if ($user->getId() !== null) {
                    $form
                        ->add('gamerTag', TextType::class, [
                            'attr' => [
                                'class' => 'form-control',
                                'minlenght' => '2',
                                'maxlenght' => '50',
                            ],
                            'label' => 'GamerTag',
                            'constraints' => [
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => 2, 'max' => 50])
                            ]
                        ])
                        ->add('email', EmailType::class, [
                            'label' => 'E-mail',
                            // if the e-mail value is "null" at edit
                            // we tell him it's an empty channel
                            // and the Validator takes over
                            'empty_data' => '',
                        ])
                        ->add('roles', ChoiceType::class, [
                            'label' => 'roles',
                            'choices' => [
                                'Utilisateur' => 'ROLE_USER',
                                'Administrateur' => 'ROLE_ADMIN',
                            ],
                            'multiple' => true,
                            // checkboxes
                            'expanded' => true,
                        ])
                        ->add('password', RepeatedType::class, [
                            // repeated field type
                            'type' => PasswordType::class,
                            'invalid_message' => 'Les mots de passe ne correspondent pas.',
                            'options' => ['attr' => ['class' => 'password-field']],
                            'required' => true,
                            // options for field 1 (the one transmitted to the back)
                            'first_options'  => ['label' => 'Nouveau mot de passe'],
                            // options for field 2
                            'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
                        ]);
                } else {
                    $form->add('password', RepeatedType::class, [
                        // repeated field type
                        'type' => PasswordType::class,
                        'invalid_message' => 'Les mots de passe ne correspondent pas.',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'required' => true,
                        // options for field 1 (the one transmitted to the back)
                        'first_options'  => ['label' => 'Mot de passe'],
                        // options for field 2
                        'second_options' => ['label' => 'Confirmer le mot de passe'],
                    ]);
                }
            })

            ->add('password', RepeatedType::class, [

                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('avatar', EntityType::class, [
                'label' => 'Jeux-video',
                'class' => Avatar::class,
                'choice_label' => 'getName',
                'help' => 'Choisis un avatar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
