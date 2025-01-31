<?php

use App\Http\Controllers\ImobiliariaController;
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

Route::get('imobiliaria', [ImobiliariaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('imobiliaria.index');

Route::view('imoveis', 'pages.imovel.index')
    ->middleware(['auth', 'verified'])
    ->name('imovel.index');

Route::view('clientes', 'pages.client.index')
    ->middleware(['auth', 'verified'])
    ->name('client.index');

Route::view('admin', 'pages.admin.index')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.index');

Route::view('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('configuracoes', 'pages.settings')
    ->middleware(['auth', 'verified'])
    ->name('settings');

require __DIR__ . '/auth.php';
