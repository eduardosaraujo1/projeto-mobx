<?php
namespace App\Services;

use App\Models\Imobiliaria;
use Session;

class ImobiliariaService
{
    public function getSelectedImobiliaria(): Imobiliaria|null
    {
        $imobiliarias = auth()->user()->imobiliarias->all();
        $index_imobiliaria = Session::get('index_imobiliaria', 0);
        return $imobiliarias[$index_imobiliaria] ?? null;
    }
}
