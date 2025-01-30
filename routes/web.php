<?php

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

Route::view('imobiliaria', 'imobiliaria.index')->name('imobiliaria.index');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('imoveis', 'imovel.index')
    ->middleware(['auth', 'verified'])
    ->name('imovel.index');

Route::view('clientes', 'client.index')
    ->middleware(['auth', 'verified'])
    ->name('client.index');

Route::view('admin', 'admin.index')
    ->middleware(['auth', 'admin'])
    ->name('admin.index');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
