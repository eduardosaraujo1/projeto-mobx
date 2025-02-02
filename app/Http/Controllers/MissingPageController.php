<?php

namespace App\Http\Controllers;

use App\Services\ImobiliariaService;
use Illuminate\Http\Request;

class MissingPageController extends Controller
{
    public function __construct(
    ) {
    }
    function index(Request $request)
    {
        $imobiliaria = ImobiliariaService::current_imobiliaria();

        if (isset($imobiliaria)) {
            return redirect()->route('imobiliaria.home');
        }

        return view('pages.imobiliaria.missing');
    }
}
