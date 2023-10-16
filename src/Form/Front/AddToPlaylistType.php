<?php

namespace App\Form\Front;

use App\Entity\Music;
use App\Entity\Playlist;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToPlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // Get user data from the Controller
        $user = $options['user'];

    $builder
        ->add('playlists', EntityType::class, [
            'label' => 'Choisir ma playlist',
            'label_attr' => [
                'class' => 'music-form__label'
            ],
            'class' => Playlist::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'required' => false,
            'help' => 'Associer cette musique à une playlist (Sélection multiple possible)',
            'help_attr' => [
                'class' => 'music-form__help'
            ],
            'attr' => [
                'class' => 'music-form__list'
            ],
            'query_builder' => function (EntityRepository $er) use ($user) {
                return $er->createQueryBuilder('p')
                    ->innerJoin('p.user', 'u')
                    ->where('u.id = :userId')
                    ->setParameter('userId', $user->getId());
            },
        ])
        ->add('Ajouter', SubmitType::class, [
            'attr' => [
                'class' => 'btn-primary btn user-form__button mt-4'
            ],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        'data_class' => Music::class,
        'user' => null, // Ajoutez une option pour 'user'
    ]);
    }
}