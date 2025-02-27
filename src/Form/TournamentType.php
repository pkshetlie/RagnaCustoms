<?php

namespace App\Form;

use App\Entity\Tournament;
use App\Entity\TournamentPlayer;
use App\Enum\ETournamentState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', null, ['label' => 'Title', 'attr' => ['class' => 'form-control']])
            ->add('description', null, ['label' => 'Description', 'attr' => ['class' => 'form-control']])
            ->add(
                'banner',
                FileType::class,
                [
                    'label' => 'Banner',
                    'attr' => ['class' => 'form-control'],
                    'required' => true,
                ]
            )
            ->add('registrationStartAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('registrationEndAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('startAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            // ->add('scoreMath', null,['label'=>'Score Math','attr'=>['class'=>'form-control']])
            ->add(
                'priceDescription',
                null,
                ['label' => 'Winners price description', 'attr' => ['class' => 'form-control']]
            )
            ->add('maxPlayers', NumberType::class, ['label' => 'Max players', 'attr' => ['class' => 'form-control']])
            // ->add('players', CollectionType::class)
            // ->add('slug')
            ->add('songDifficulties', SongDifficultyAutoCompleteField::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('tournamentPlayers', CollectionType::class, [
                'entry_type' => TournamentPlayerType::class,
                'attr' => ['class' => 'form-control'],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    ETournamentState::UNDER_CONSTRUCTION->name => ETournamentState::UNDER_CONSTRUCTION->value,
                    ETournamentState::RELEASED->name => ETournamentState::RELEASED->value,
                ],
                'label' => 'State',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
