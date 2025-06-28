<?php

namespace App\Interface;

use App\Entity\Utilisateur;

interface INotificationService
{
    public function send(Utilisateur $utilisateur, string $message);
}
