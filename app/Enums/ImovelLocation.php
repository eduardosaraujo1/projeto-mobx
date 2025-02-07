<?php

namespace App\Enums;

use Str;

enum ImovelLocation: int
{
    case PRAIA = 0;
    case MORRO = 1;

    public static function randomId()
    {
        return array_rand(static::cases());
    }

    public function getName()
    {
        return Str::title($this->name);
    }
}
