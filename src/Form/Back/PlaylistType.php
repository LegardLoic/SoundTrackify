<?php

namespace App\Form\Back;

use App\Entity\Music;
use App\Entity\Playlist;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $filteredMusics = $options['filtered_musics'];
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Chill Time',
                ],
                'empty_data' => "",
                'label' => 'Nom de la playlist',
                'help' => 'Le nom de la playlist',
            ])
            ->add('user',EntityType::class, [
                'label' => 'Utilisateur',
                'class' => User::class,
                'help' => 'Associer à un utilisateur',
                'choice_label' => 'getGamerTag'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
            'filtered_musics' => []
        ]);
    }
}
