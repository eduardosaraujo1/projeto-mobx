<?php

namespace App\Enums;

enum AccessLevel: int
{
    case COLABORADOR = 0;
    case GERENTE = 1;

    public static function randomId()
    {
        return array_rand(static::cases());
    }
}
