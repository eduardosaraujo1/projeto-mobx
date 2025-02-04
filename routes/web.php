<?php

use App\Facades\SelectedImobiliaria;
use App\Http\Controllers\Search\SearchClients;
use App\Http\Controllers\Search\SearchImobiliarias;
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
    // Home redirect
    Route::get('/home', function () {
        $redirect_route = auth()->user()->is_admin ? route('admin.index') : route('imobiliaria.home');

        return redirect($redirect_route);
    })->name('home');

    // navbar
    Route::view('dashboard', 'pages.imobiliaria.dashboard')
        ->name('dashboard');

    Volt::route('minha-imobiliaria', 'info.imobiliaria')
        ->name('imobiliaria.home');

    Volt::route('imoveis', 'search.imoveis')
        ->name('imovel.index');

    Volt::route('clientes', 'search.clients')
        ->name('client.index');

    // create
    Volt::route('imovel/novo', 'create.imovel')
        ->name('imovel.new');

    Volt::route('cliente/novo', 'create.client')
        ->name('client.new');

    Route::view('usuario/novo', 'pages.user.create')
        ->name('user.new');

    // info/edit
    Volt::route('imovel/{imovel}/info', 'info.imovel')
        ->name('imovel.info');

    Volt::route('cliente/{client}/info', 'info.client')
        ->name('client.info');

    // search api routes
    Route::get('/api/search/imobiliarias', SearchImobiliarias::class);
    Route::get('/api/search/clients', SearchClients::class);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // user dropdown nav
    Route::view('admin', 'pages.admin.index')
        ->middleware(['admin'])
        ->name('admin.index');

    Route::view('configuracoes', 'pages.user.settings')
        ->name('settings');

    // create
    Route::view('imobiliaria/novo', 'pages.imobiliaria.create')
        ->name('imobiliaria.new');

    // missing imobiliaria
    Route::get('error', function () {
        $imobiliaria = SelectedImobiliaria::get(auth()->user());

        if (isset($imobiliaria)) {
            return redirect()->route('home');
        }

        return view('pages.imobiliaria.missing');
    })->name('imobiliaria.missing');
});

require __DIR__ . '/auth.php';
