<?php

namespace App\Form;

use App\Entity\Tournament;
use App\Entity\TournamentPlayer;
use App\Entity\Utilisateur;
use App\Enum\ETournamentPlayerStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TournamentPlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, ['choices' => [
                ETournamentPlayerStatus::WAITING->value => ETournamentPlayerStatus::WAITING->name,
                ETournamentPlayerStatus::APPROVED->value => ETournamentPlayerStatus::APPROVED->name,
                ETournamentPlayerStatus::REFUSED->value => ETournamentPlayerStatus::REFUSED->name,
            ]])
            ->add('user', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'id',
            ])
            ->add('tournament', EntityType::class, [
                'class' => Tournament::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TournamentPlayer::class,
        ]);
    }
}
