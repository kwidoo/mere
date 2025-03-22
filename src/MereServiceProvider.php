<?php

namespace Kwidoo\Mere;

use Illuminate\Support\ServiceProvider;
use Kwidoo\Mere\Contracts\MenuRepository;
use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Http\Middleware\BindResource;
use Kwidoo\Mere\Repositories\MenuRepositoryEloquent;
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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $router = $this->app['router'];
        $router->aliasMiddleware('bind.resource', BindResource::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('mere.php'),
            ], 'config');
            $this->commands([
                \Kwidoo\Mere\Console\Commands\SyncMenuCommand::class,
            ]);
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
        $this->app->bind(MenuRepository::class, MenuRepositoryEloquent::class);


        // Register the main class to use with the facade
        $this->app->singleton('mere', function () {
            return new Mere;
        });
    }
}
