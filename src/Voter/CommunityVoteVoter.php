<?php

namespace App\Voter;

use App\Entity\ScoreHistory;
use App\Entity\Song;
use App\Entity\SongDifficulty;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommunityVoteVoter extends Voter
{

    const VOTE = 'vote';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if ($attribute != self::VOTE) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof SongDifficulty) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            // $vote?->addReason('The user is not logged in.');
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var SongDifficulty $songDifficulty */
        $songDifficulty = $subject;

        return match($attribute) {
            self::VOTE => $this->canVote($songDifficulty, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canVote(SongDifficulty $songDifficulty, Utilisateur $user): bool
    {
        // Check if user is registered for more than 1 month
        $oneMonthAgo = new \DateTime('-1 month');

        if ($user->getCreatedAt() > $oneMonthAgo) {
            return false;
        }

        // Check if user has played this difficulty
        $playedDifficulty = $user->getScoreHistories()->filter(function(ScoreHistory $sh) use($songDifficulty) {
            return $sh->getSongDifficulty()->getId() === $songDifficulty->getId();
        })->count();

        if ($playedDifficulty < 1) {
            return false;
        }

        // Check if user has played more than 5 different difficulties
        $playedDifficultiesCount = $user->getScoreHistories()->filter(function(ScoreHistory $sh) use($songDifficulty) {
            return $sh->getSongDifficulty()->getLevel() === $songDifficulty->getLevel();
        })->count();

        //on 5 different songs
        $playedSongsCount =  $user->getScoreHistories()->filter(function(ScoreHistory $sh) use($songDifficulty) {
            return $sh->getSongDifficulty()->getLevel() === $songDifficulty->getLevel();
        })->toArray();
        $playedSongsCount = array_unique(array_map(function($sh) {
            return $sh->getSongDifficulty()->getSong()->getId();
        }, $playedSongsCount));

        $playedSongsCount = count($playedSongsCount);

        if ($playedDifficultiesCount < 5 || $playedSongsCount < 5) {
            return false;
        }

        return true;
    }
}
