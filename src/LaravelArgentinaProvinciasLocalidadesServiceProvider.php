<?php

namespace Cardumen\LaravelArgentinaProvinciasLocalidades;

use Illuminate\Support\ServiceProvider;

class LaravelArgentinaProvinciasLocalidadesServiceProvider extends ServiceProvider
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
                \Cardumen\LaravelArgentinaProvinciasLocalidades\Commands\CargarProvinciasLocalidades::class,
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
