<?php

namespace App\Enums;

enum ClientType: int
{
    case LOCADOR = 0;
    case VENDEDOR = 1;

    public static function randomId()
    {
        return array_rand(static::cases());
    }
}
