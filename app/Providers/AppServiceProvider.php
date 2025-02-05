<?php

namespace App\Providers;

use App\Facades\SelectedImobiliaria;
use App\Models\Imobiliaria;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Imobiliaria::class, function () {
            return SelectedImobiliaria::get(auth()->user()) ?? new Imobiliaria;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
