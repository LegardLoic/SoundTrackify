<?php

namespace App\Form\Back;

use App\Entity\Platform;
use App\Entity\Videogame;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideogameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex: Doom Eternal',
                ],
                'empty_data' => "",
                'label' => 'Nom',
                'help' => 'Le nom du jeux-video',
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Image du jeux-video',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https',
                'required' => false,
            ])
            ->add('resume', TextareaType::class, [
                'label' => 'Résumé',
                'help' => '300 caractères max.',
                'attr' => [
                    'rows' => 3,
                    
                    'help' => '300 caractères max.'
                ],
                'empty_data' => '',
                'required' => false,
            ])
            ->add('releaseDate', DateType::class, [
                'attr' => ['class' => 'datepicker'],
                'widget' => 'single_text',
                'help' => 'date de sortie',
                'required' => false,
            ])
            ->add('platforms', EntityType::class, [
                'class' => Platform::class,
                'choice_label' => 'name',
                // many Platform (ToMany)
                'multiple' => true,
                // checkboxes
                'expanded' => true,
                'help' => 'Associer à une plateforme (Sélection multiple possible)',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videogame::class,
        ]);
    }
}
