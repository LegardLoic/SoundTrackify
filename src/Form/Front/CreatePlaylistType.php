<?php

namespace App\Form\Front;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CreatePlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Mario playlist',
                    'class' => 'form-control',
                ],
                'empty_data' => "",
                'label' => 'Nom de la playlist',
                'label_attr' => [
                    'class' => 'form-label  mt-4 user-form__title'
                ],
                'help' => 'Le nom de la playlist',
            ])
            ->add('Ajouter',SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary btn user-form__button mt-4'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
