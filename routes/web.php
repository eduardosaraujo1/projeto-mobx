<?php

use App\Facades\SelectedImobiliaria;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::prefix('legacy')->name('legacy.')->group(
    function () {
        Route::view('/', 'legacy.index')->name('index');
        Route::view('/imoveis', 'legacy.imoveis')->name('imoveis');
        Route::view('/cadastro', 'legacy.cadastro')->name('cadastro');
        Route::view('/imobiliaria', 'legacy.imobiliaria')->name('imobiliaria');
        Route::view('/configuracoes', 'legacy.configuracoes')->name('configuracoes');
    }
);

Route::get('/', function () {
    return redirect(Auth::check() ? '/home' : '/login');
});

Route::middleware(['auth', 'verified', 'has-imobiliaria'])->group(function () {

    // Navbar
    // home redirect
    Route::get('/home', function () {
        $name = auth()->user()->is_admin ? 'admin.index' : 'imobiliaria.home';

        return redirect()->route($name);
    })->name('home');

    Route::view('dashboard', 'pages.dashboard')
        ->name('dashboard');

    Volt::route('imobiliaria', 'pages.info.imobiliaria')
        ->name('imobiliaria.home');

    Volt::route('imoveis', 'pages.search.imoveis')
        ->name('imovel.index');

    Volt::route('clientes', 'pages.search.clients')
        ->name('client.index');

    // create
    Volt::route('imovel/novo', 'pages.create.imovel')
        ->name('imovel.new');

    Volt::route('cliente/novo', 'pages.create.client')
        ->name('client.new');

    Volt::route('usuario/novo', 'pages.create.user')
        ->name('user.new');
    // new imobiliaria does not require the 'has-imobiliaria' middleware and is in another route group

    // info/edit
    Volt::route('imovel/{imovel}/info', 'pages.info.imovel')
        ->name('imovel.info');

    Volt::route('cliente/{client}/info', 'pages.info.client')
        ->name('client.info');

    Volt::route('user/{user}/info', 'pages.info.user')
        ->name('user.info');

    Volt::route('imobiliaria/{imobiliaria}/info', 'pages.info.imobiliaria')
        ->middleware('admin')
        ->name('imobiliaria.info');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // user dropdown nav
    Route::view('admin', 'pages.admin.index')
        ->middleware(['admin'])
        ->name('admin.index');

    Route::view('configuracoes', 'pages.user.settings')
        ->name('settings');

    // create imobiliaria
    Volt::route('imobiliaria/novo', 'pages.create.imobiliaria')
        ->middleware('admin')
        ->name('imobiliaria.new');

    // missing imobiliaria
    Route::get('error', function () {
        $imobiliaria = SelectedImobiliaria::get(auth()->user());

        if (isset($imobiliaria)) {
            return redirect()->route('home');
        }

        return view('pages.missing');
    })->name('imobiliaria.missing');
});

require __DIR__.'/auth.php';
