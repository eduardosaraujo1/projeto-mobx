<?php

use App\Http\Controllers\ImobiliariaController;
use App\Http\Controllers\MissingPageController;
use App\Http\Middleware\EnsureUserHasImobiliaria;
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
    return redirect(Auth::check() ? '/imobiliaria' : '/login');
});

Route::middleware(['auth', 'verified', 'hasImobiliaria'])->group(function () {
    // navbar
    Route::view('imobiliaria', 'pages.imobiliaria.index')
        ->name('imobiliaria.index');

    Route::view('imoveis', 'pages.imovel.index')
        ->name('imovel.index');

    Route::view('clientes', 'pages.client.index')
        ->name('client.index');

    Route::view('dashboard', 'pages.imobiliaria.dashboard')
        ->name('dashboard');
});

// imobiliaria error handling
Route::get('missing-imobiliaria', [MissingPageController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('imobiliaria.missing');


// user dropdown nav
Route::view('admin', 'pages.admin.index')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.index');

Route::view('configuracoes', 'pages.user.settings')
    ->middleware(['auth', 'verified'])
    ->name('settings');

// cadastro
Route::view('imobiliaria/novo', 'pages.imobiliaria.create')
    ->middleware(['auth', 'verified'])
    ->name('imobiliaria.new');

Route::view('imovel/novo', 'pages.imovel.create')
    ->middleware(['auth', 'verified'])
    ->name('imovel.new');

Route::view('cliente/novo', 'pages.client.create')
    ->middleware(['auth', 'verified'])
    ->name('client.new');

Route::view('usuario/novo', 'pages.user.create')
    ->middleware(['auth', 'verified'])
    ->name('user.new');

// visualizacao e edicao
Route::view('imovel/{imovel}/info', 'pages.imovel.info')
    ->middleware(['auth', 'verified'])
    ->name('imovel.info');

Route::view('cliente/{client}/info', 'pages.client.info')
    ->middleware(['auth', 'verified'])
    ->name('client.info');

require __DIR__ . '/auth.php';
