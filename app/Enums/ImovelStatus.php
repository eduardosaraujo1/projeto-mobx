<?php

namespace App\Enums;

use Str;

enum ImovelStatus: int
{
    case LIVRE = 0;
    case ALUGADO = 1;
    case VENDIDO = 2;

    public static function randomId()
    {
        return array_rand(static::cases());
    }

    public function getName()
    {
        return Str::title($this->name);
    }
}
