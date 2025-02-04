<?php

namespace App\Enums;

use Str;

enum UserRole: int
{
    case COLABORADOR = 0;
    case GERENTE = 1;

    public static function randomId()
    {
        return array_rand(static::cases());
    }

    public function getName()
    {
        return Str::title($this->name);
    }
}
