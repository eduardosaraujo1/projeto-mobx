<?php

namespace App\Http\Controllers;

use App\Models\Imobiliaria;
use App\Services\ImobiliariaService;
use Illuminate\Http\Request;
use Session;

class ImobiliariaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imobiliarias = auth()->user()->imobiliarias->all();
        $index_imobiliaria = Session::get('index_imobiliaria', 0);
        $imobiliaria = $imobiliarias[$index_imobiliaria] ?? null;

        return view('pages.imobiliaria.index', [
            'imobiliaria' => $imobiliaria
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Imobiliaria $imobiliaria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Imobiliaria $imobiliaria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imobiliaria $imobiliaria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imobiliaria $imobiliaria)
    {
        //
    }
}
