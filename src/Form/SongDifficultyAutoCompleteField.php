<?php

namespace App\Form;

use App\Entity\SongDifficulty;
use App\Entity\Utilisateur;
use App\Repository\SongDifficultyRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class SongDifficultyAutoCompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => SongDifficulty::class,
            'filter_query' => function (QueryBuilder $qb, string $query, EntityRepository $repository) {
                if (!$query) {
                    return;
                }

                $qb->andWhere(
                    $qb->expr()->orX(
                        's.name LIKE :search',
                        's.authorName LIKE :search',

                    )
                )
                    ->setParameter('search','%'.$query.'%');
            },
            'query_builder' => function (SongDifficultyRepository $songDifficultyRepository) {
                return $songDifficultyRepository
                    ->createQueryBuilder("sd")
                    ->distinct()
                    ->leftJoin('sd.song', 's')
                    ->andWhere('s.isDeleted = false')
                    ->andWhere('s.wip = false')
                    ->andWhere('s.moderated = true')
                    ->andWhere('s.active = true')
                    ->andWhere('s.isPrivate = false')
                    ->orderBy('s.name', 'ASC');
            },
            'tom_select_options' => [
                'openOnFocus'=>false,
                'placeholder'=>'Enter a song title (they need to be public and not WIP)',
                'hidePlaceholder'=>true,
            ],
            'multiple' => true,
            // 'autocomplete'=>true,
            "label" => 'Song Difficulties',
            'placeholder' => 'Enter a song title (they need to be public and not WIP)',
            'required' => false,

            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
