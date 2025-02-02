<?php

namespace App\Services;

use App\Enums\AccessLevel;
use Session;
use App\Models\Imobiliaria;

class ImobiliariaService
{
    public static function current_imobiliaria(): Imobiliaria|null
    {
        // get user imobiliarias
        $imobiliarias = auth()->user()->imobiliarias->all();

        // get stored index
        $index_imobiliaria = Session::get('index_imobiliaria', 0);

        // get the imobiliaria
        return $imobiliarias[$index_imobiliaria] ?? null;
    }

    public static function current_access_level(): AccessLevel|null
    {
        return AccessLevel::tryFrom(static::current_imobiliaria()->access->level ?? null);
    }
}
