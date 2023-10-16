<?php

namespace App\Form\Back;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Avatar 50',
                ],
                'empty_data' => "",
                'label' => 'Nom de l\'avatar',
                'help' => 'Le nom de l\'avatar',
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image de l\'avatar',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
        ]);
    }
}
