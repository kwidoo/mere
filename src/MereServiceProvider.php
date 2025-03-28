<?php

namespace Kwidoo\Mere;

use Illuminate\Support\ServiceProvider;
use Kwidoo\Mere\Console\Commands\SyncMenuCommand;
use Kwidoo\Mere\Contracts\Eventable;
use Kwidoo\Mere\Contracts\MenuRepository;
use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Contracts\Transactional;
use Kwidoo\Mere\Factories\LaravelEvents;
use Kwidoo\Mere\Repositories\MenuRepositoryEloquent;
use Kwidoo\Mere\Services\MenuService;
use Kwidoo\Mere\Factories\LaravelTransactions;
use Kwidoo\Mere\Contracts\Lifecycle;
use Kwidoo\Mere\Executors\LifecycleExecutor;

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
        // $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('mere.php'),
            ], 'config');
            $this->publishes([
                __DIR__ . '/../resources/js' => resource_path('js/vendor/kwidoo/mere'),
            ], 'kwidoo-mere-assets');
            $this->commands([
                SyncMenuCommand::class,
            ]);
            $resources = config('mere.resources', []);
            if (!empty($resources)) {
                $firstService = reset($resources);
                $this->app->bind(\Kwidoo\Mere\Contracts\BaseService::class, $firstService);
            }
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
        $this->app->bind(Transactional::class, LaravelTransactions::class);
        $this->app->bind(Eventable::class, LaravelEvents::class);
        $this->app->bind(Lifecycle::class, LifecycleExecutor::class);

        // Register the main class to use with the facade
        $this->app->singleton('mere', function () {
            return new Mere;
        });
    }
}
