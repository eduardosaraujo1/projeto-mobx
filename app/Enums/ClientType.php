<?php

namespace App\Enums;

use Str;

enum ClientType: int
{
    case LOCADOR = 0;
    case VENDEDOR = 1;

    public static function randomId()
    {
        return array_rand(static::cases());
    }

    public function getName()
    {
        return Str::title($this->name);
    }
}
