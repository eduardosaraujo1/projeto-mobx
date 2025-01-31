<?php

namespace App\Http\Controllers;

use App\Services\ImobiliariaService;
use Illuminate\Http\Request;

class MissingPageController extends Controller
{
    public function __construct(
        protected ImobiliariaService $service
    ) {
    }
    function index(Request $request)
    {
        $imobiliaria = $this->service->getSelectedImobiliaria();

        if (isset($imobiliaria)) {
            return redirect()->route('imobiliaria.index');
        }

        return view('pages.imobiliaria.missing');
    }
}
