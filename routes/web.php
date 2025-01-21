<?php

use Illuminate\Support\Facades\Route;

Route::prefix('legacy')->group(
    function () {
        Route::view('/', 'legacy.index')->name('legacy.index');
        Route::view('/imoveis', 'legacy.imoveis')->name('legacy.imoveis');
        Route::view('/cadastro', 'legacy.cadastro')->name('legacy.cadastro');
        Route::view('/imobiliaria', 'legacy.imobiliaria')->name('legacy.imobiliaria');
        Route::view('/configuracoes', 'legacy.configuracoes')->name('legacy.configuracoes');
    }
);

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
