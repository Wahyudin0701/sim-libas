<?php

namespace App\Providers;

use App\Services\CitizenServiceCatalog;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Bagikan data katalog layanan warga ke seluruh tampilan publik dan layout
        View::composer(['layouts.public', 'public.*'], function ($view) {
            $view->with('citizenServices', CitizenServiceCatalog::all());
        });
    }
}
