<?php
namespace App\Facades;

use App\Services\SelectedImobiliariaService;
use Illuminate\Support\Facades\Facade;

class SelectedImobiliaria extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SelectedImobiliariaService::class;
    }
}
