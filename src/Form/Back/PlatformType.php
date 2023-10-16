<?php

namespace App\Form\Back;

use App\Entity\Platform;
use App\Entity\Videogame;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlatformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Playstation 6',
                ],
                'empty_data' => "",
                'label' => 'Nom de la plateforme',
                'help' => 'Le nom de la plateforme',
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image de la plateforme',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Platform::class,
        ]);
    }
}
