<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // The UI is Bootstrap 5, so the paginator must render Bootstrap markup
        // rather than the framework default (Tailwind). The Bootstrap view is
        // overridden in resources/views/vendor/pagination with Indonesian labels.
        Paginator::useBootstrapFive();
    }
}
