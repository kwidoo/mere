<?php

namespace Kwidoo\Mere;

use Illuminate\Support\ServiceProvider;
use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Services\MenuService;

class MereServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mere');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mere');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('mere.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/mere'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/mere'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/mere'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mere');


        $this->app->bind(MenuServiceContract::class, MenuService::class);


        // Register the main class to use with the facade
        $this->app->singleton('mere', function () {
            return new Mere;
        });
    }
}
