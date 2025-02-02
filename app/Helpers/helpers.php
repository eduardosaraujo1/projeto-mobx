<?php

use App\Models\Imobiliaria;
if (!function_exists('current_imobiliaria')) {
    function current_imobiliaria(): Imobiliaria|null
    {
        $imobiliarias = auth()->user()->imobiliarias->all();
        $index_imobiliaria = Session::get('index_imobiliaria', 0);
        return $imobiliarias[$index_imobiliaria] ?? null;
    }
}
