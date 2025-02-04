<?php

namespace App\Providers;

use App\Services\SelectedImobiliariaService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class SelectedImobiliariaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SelectedImobiliariaService::class, function (Application $app) {
            return new SelectedImobiliariaService;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    }
}
