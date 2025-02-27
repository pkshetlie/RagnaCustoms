<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ETournamentPlayerStatus: string implements TranslatableInterface
{
    case WAITING = 'waiting';
    case APPROVED = 'approved';
    case REFUSED = 'refused';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, locale: $locale);
    }
}
