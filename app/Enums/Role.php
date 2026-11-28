<?php

namespace App\Enums;

enum Role: string
{
    case Superadmin = 'superadmin';
    case Trabajador = 'trabajador';

    public function label(): string
    {
        return match ($this) {
            self::Superadmin => 'Superadministrador',
            self::Trabajador => 'Trabajador',
        };
    }
}
