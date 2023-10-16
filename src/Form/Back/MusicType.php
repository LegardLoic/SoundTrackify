<?php

namespace App\Form\Back;

use App\Entity\Album;
use App\Entity\Music;
use App\Entity\Playlist;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Main Theme',
                ],
                'empty_data' => "",
                'label' => 'Nom',
                'help' => 'Le nom de la musique',
            ])
            ->add('time', TimeType::class, [
                'input'  => 'datetime',
                'label' => 'Durée',
                'widget' => 'choice',
                'hours' => range(0, 23),
                'minutes' => range(0, 59),
                'seconds' => range(0, 59),
                'with_seconds' => true,
                'help' => 'Le nom de la musique',
            ])
            ->add('link', UrlType::class, [
                'label' => 'Url',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'help' => 'Le lien url de la musique en .mp3',
                'required' => false,
            ])
            ->add('album',EntityType::class, [
                'label' => 'Album',
                'class' => Album::class,
                'choice_label' => 'getName',
                'help' => 'Album (si pas dans la liste, il faut le créer)',
            ])
            ->add('playlists', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                // several genres (ManyToMany)
                'multiple' => true,
                // extensive range (checkboxes)
                'expanded' => true,
                'required' => false,
                'help' => 'Associer à une playlist (Sélection multiple possible)',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Music::class,
        ]);
    }
}
