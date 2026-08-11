<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Agent = 'agent';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Agent => 'Agent',
            self::User => 'User',
        };
    }
}
