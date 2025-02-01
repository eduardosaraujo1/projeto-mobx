<?php

namespace App\Services;

use App\Enums\AccessLevel;
use Session;

class ImobiliariaService
{
    public static function current_imobiliaria()
    {
        $imobiliarias = auth()->user()->imobiliarias->all();
        $index_imobiliaria = Session::get('index_imobiliaria', 0);
        return $imobiliarias[$index_imobiliaria] ?? null;
    }

    public static function current_access_level(): AccessLevel|null
    {
        return AccessLevel::tryFrom(static::current_imobiliaria()->access->level ?? null);
    }
}
