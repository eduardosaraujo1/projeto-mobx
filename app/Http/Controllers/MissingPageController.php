<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MissingPageController extends Controller
{
    public function __construct(
    ) {
    }
    function index(Request $request)
    {
        $imobiliaria = current_imobiliaria();

        if (isset($imobiliaria)) {
            return redirect()->route('imobiliaria.index');
        }

        return view('pages.imobiliaria.missing');
    }
}
