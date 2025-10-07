<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\InstanciaConjunto;
use App\Observers\InstanciaConjuntoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Bootstrap para la paginación
        Paginator::useBootstrapFive();

        // Registrar observers
        InstanciaConjunto::observe(InstanciaConjuntoObserver::class);
    }
}
