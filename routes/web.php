<?php

use App\Http\Controllers\ImobiliariaController;
use App\Http\Controllers\MissingPageController;
use App\Http\Middleware\EnsureUserHasImobiliaria;
use App\Services\ImobiliariaService;
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
    Route::get('/home', function () {
        $is_admin = auth()->user()->is_admin ?? false;
        return redirect($is_admin ? route('admin.index') : route('imobiliaria.home'));
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
    Route::view('imovel/novo', 'pages.imovel.create')
        ->name('imovel.new');

    Volt::route('cliente/novo', 'create.client')
        ->name('client.new');

    Route::view('usuario/novo', 'pages.user.create')
        ->name('user.new');

    // visualizacao e edicao
    Volt::route('imovel/{imovel}/info', 'info.imovel')
        ->name('imovel.info');

    Volt::route('cliente/{client}/info', 'info.client')
        ->name('client.info');

    // missing imobiliaria
    Route::get('missing-imobiliaria', function () {
        $imobiliaria = ImobiliariaService::current_imobiliaria();

        if (isset($imobiliaria)) {
            return redirect()->route('imobiliaria.home');
        }

        return view('pages.imobiliaria.missing');
    })->name('imobiliaria.missing');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // user dropdown nav
    Route::view('admin', 'pages.admin.index')
        ->middleware(['admin'])
        ->name('admin.index');

    Route::view('configuracoes', 'pages.user.settings')
        ->name('settings');

    // cadastro
    Route::view('imobiliaria/novo', 'pages.imobiliaria.create')
        ->name('imobiliaria.new');
});

require __DIR__ . '/auth.php';
