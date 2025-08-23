<?php

namespace App\Form;

use App\Entity\SongDifficulty;
use App\Entity\SongDifficultyNotation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongDifficultyNotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $level = -100; // Niveau par défaut si "level" n’est pas fourni

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $entity = $event->getData(); // Ici tu as ton entité (ou null si création)
            $form   = $event->getForm();
            $level = $entity->getSongDifficulty()->getDifficultyRank()->getLevel();

            // Calculer les choix dynamiquement
            $min = max(1, $level - 4); // Limiter à 1 minimum
            $max = min(10, $level + 4); // Limiter à 10 maximum
            $choices = range($min, $max);

            // Générer les choix au format clé-valeur
            $formattedChoices = [];
            foreach ($choices as $choice) {
                $formattedChoices[(string)$choice] = (string)$choice;
            }

            $form->add('note', ChoiceType::class, [
                'label' => false,
                'choices'=>$formattedChoices]);

        });





        $builder
            ->add('songDifficulty', HiddenType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SongDifficultyNotation::class,
            'level' => null, // Initialiser l'option "level" à null par défaut
        ]);
    }
}
