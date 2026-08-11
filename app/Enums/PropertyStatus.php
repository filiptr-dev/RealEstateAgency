<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Apartment = 'apartment';
    case House = 'house';
    case Villa = 'villa';
    case Land = 'land';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
