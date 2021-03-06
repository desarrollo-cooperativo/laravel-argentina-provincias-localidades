<?php

namespace Cardumen\ArgentinaProvinciasLocalidades;

use Illuminate\Support\ServiceProvider;

class ArgentinaProvinciasLocalidades extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CargarProvinciasLocalidades::class,
                Commands\DestacarProvinciasLocalidades::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


}
