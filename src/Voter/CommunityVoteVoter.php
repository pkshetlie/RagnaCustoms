<?php

namespace App\Voter;

use App\Entity\SongDifficulty;
use App\Entity\Utilisateur;
use App\Repository\ScoreHistoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommunityVoteVoter extends Voter
{

    public function __construct(
        private readonly ScoreHistoryRepository $scoreHistoryRepository
    ) {
    }

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

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            $vote?->addReason('The user is not logged in.');
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var SongDifficulty $songDifficulty */
        $songDifficulty = $subject;

        return match($attribute) {
            self::VOTE => $this->canVote($songDifficulty, $user, $vote),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canVote(SongDifficulty $songDifficulty, Utilisateur $user, ?Vote $vote): bool
    {
        // Allow admins to vote without restrictions
        if (in_array('ROLE_MODERATOR', $user->getRoles(), true) && $songDifficulty->getSong()->getMappers()->contains($user)) {
            return true;
        }

        // Check if user is registered for more than 1 month
        $oneMonthAgo = new \DateTime('-1 month');

        if ($user->getCreatedAt() > $oneMonthAgo) {
            $vote?->addReason('You must be registered for at least 1 month.');
            return false;
        }

        $playerHistory = new ArrayCollection(
            $this->scoreHistoryRepository->findBy(['user' => $user, 'songDifficulty' => $songDifficulty])
        );

        // Check if user has played this difficulty
        $playedDifficulty = $playerHistory->count();

        $playerHistorySameLevel = new ArrayCollection(
            $this->scoreHistoryRepository->findByUserAndDifficulty($user, $songDifficulty)
        );
        
        if ($playedDifficulty < 1) {
            $vote?->addReason('You must have played this difficulty.');
            return false;
        }
        // Check if user has played more than 5 different difficulties
        $playedDifficultiesCount = $playerHistorySameLevel->count();

        //on 5 different songs
        $playedSongsCount = array_unique(array_map(function($sh) {
            return $sh->getSongDifficulty()?->getSong()?->getId();
        }, $playerHistorySameLevel->toArray()));

        $playedSongsCount = count($playedSongsCount);
        
        if ($playedDifficultiesCount < 6 || $playedSongsCount < 6) {
            $vote?->addReason('You must have played at least 6 songs and 6 difficulties.');
            return false;
        }

        return true;
    }
}
