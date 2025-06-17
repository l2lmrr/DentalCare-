<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PRATICIEN = 'praticien';
    case PATIENT = 'patient';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}