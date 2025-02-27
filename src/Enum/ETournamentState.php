<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ETournamentState: string implements TranslatableInterface
{
    case UNDER_CONSTRUCTION = 'under_construction';
    case RELEASED = 'released';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, locale: $locale);
    }
}
