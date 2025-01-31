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

// navbar
Route::get('imobiliaria', [ImobiliariaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('imobiliaria.index');

Route::view('imoveis', 'pages.imovel.index')
    ->middleware(['auth', 'verified'])
    ->name('imovel.index');

Route::view('clientes', 'pages.client.index')
    ->middleware(['auth', 'verified'])
    ->name('client.index');

Route::view('dashboard', 'pages.imobiliaria.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// user dropdown nav
Route::view('admin', 'pages.admin.index')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin.index');

Route::view('configuracoes', 'pages.user.settings')
    ->middleware(['auth', 'verified'])
    ->name('settings');

// cadastro
Volt::route('imobiliaria/novo', 'cadastro.imobiliaria')
    ->middleware(['auth', 'verified'])
    ->name('imobiliaria.new');

Volt::route('imovel/novo', 'cadastro.imovel')
    ->middleware(['auth', 'verified'])
    ->name('imovel.new');

Volt::route('cliente/novo', 'cadastro.client')
    ->middleware(['auth', 'verified'])
    ->name('client.new');

Volt::route('usuario/novo', 'cadastro.user')
    ->middleware(['auth', 'verified'])
    ->name('user.new');

require __DIR__ . '/auth.php';
