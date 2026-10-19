<?php

namespace App\Providers;

use App\Services\CitizenServiceCatalog;
use Illuminate\Support\Facades\URL;
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
        if (config('app.env') === 'production' || isset($_SERVER['HTTP_CF_VISITOR'])) {
            URL::forceScheme('https');
        }

        // Bagikan data katalog layanan warga ke seluruh tampilan publik dan layout
        View::composer(['layouts.public', 'public.*'], function ($view) {
            $view->with('citizenServices', CitizenServiceCatalog::all());
        });
    }
}
