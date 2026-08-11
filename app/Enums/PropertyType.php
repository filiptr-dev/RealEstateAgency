<?php

namespace App\Enums;

enum PropertyType: string
{
    case Sale = 'sale';
    case Rent = 'rent';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'For Sale',
            self::Rent => 'For Rent',
        };
    }
}
