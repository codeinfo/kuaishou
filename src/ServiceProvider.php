<?php

namespace Codeinfo\LaravelKuaishou;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/kuaishou.php' => config_path('kuaishou.php'),
        ], 'laravel-kuaishou');
    }
}
