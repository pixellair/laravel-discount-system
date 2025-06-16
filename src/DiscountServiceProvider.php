<?php

namespace DiscountSystem;

use Illuminate\Support\ServiceProvider;

class DiscountServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'discount');

        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');

        // Enable publishing of migrations
        $this->publishes([
            __DIR__.'/Database/migrations' => database_path('migrations'),
        ], 'laravel-discount-system-migrations');
    }

    public function register()
    {

    }
}

