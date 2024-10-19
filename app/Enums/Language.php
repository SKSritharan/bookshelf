<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Language: string implements HasLabel
{
    case English = 'en';
    case Tamil = 'ta';
    case Sinhala = 'sn';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::English => 'English',
            self::Tamil => 'Tamil',
            self::Sinhala => 'Sinhala',
        };
    }
}
