<?php

namespace App\Interface;

use App\Entity\Song;
use App\Entity\Vote;

interface IDiscordService
{
    public function sendWipSongMessage(Song $song);

    public function sendNewSongMessage(Song $song);

    public function sendUpdatedSongMessage(Song $song);

    public function sendFeedback(Vote $feedback);

    public function deletedSong(Song $song);

    public function rankedSong(Song $song);
}
