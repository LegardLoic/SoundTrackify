<?php

namespace App\Form\Back;

use App\Entity\Album;
use App\Entity\Videogame;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Doom Eternal Original SoundTrack',
                ],
                'empty_data' => "",
                'label' => 'Nom de l\'album',
                'help' => 'Le nom de l\'album',
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image de l\'album',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'required' => false,
            ])
            ->add('releaseDate', DateType::class, [
                'attr' => [
                    'class' => 'datepicker'
                ],
                'widget' => 'single_text',
                'help' => 'date de sortie',
                'required' => false,
            ])
            ->add('mainThemeUrl', UrlType::class, [
                'label' => 'Theme principal',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'required' => false,
            ])
            ->add('videogame',EntityType::class, [
                'label' => 'Jeux-video',
                'class' => Videogame::class,
                'choice_label' => 'getName',
                'help' => 'Le jeux video associé (si pas dans la liste, il faut le créer)'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
