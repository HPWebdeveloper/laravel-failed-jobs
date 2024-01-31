<?php

namespace HPWebdeveloper\LaravelFailedJobs;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Queue\QueueManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FailedJobsServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
        $this->offerPublishing();
        $this->registerCommands();
    }

    /**
     * Register the FailedJobs routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        Route::group([
            'domain' => config('failedjobs.domain', null),
            'prefix' => config('failedjobs.path'),
            'namespace' => 'HPWebdeveloper\LaravelFailedJobs\Http\Controllers',
            'middleware' => config('failedjobs.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the FailedJobs resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'failedjobs');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            FAILEDJOBS_PATH.'/public' => public_path('vendor/failedjobs'),
        ], ['failedjobs-assets', 'laravel-assets']);
    }

    /**
     * Setup the resource publishing groups for FailedJobs.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/FailedJobsServiceProvider.stub' => app_path('Providers/FailedJobsServiceProvider.php'),
            ], 'failedjobs-provider');

            $this->publishes([
                __DIR__.'/../config/failedjobs.php' => config_path('failedjobs.php'),
            ], 'failedjobs-config');
        }
    }

    /**
     * Register the FailedJobs Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\PublishCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('FAILEDJOBS_PATH')) {
            define('FAILEDJOBS_PATH', realpath(__DIR__.'/../'));
        }

        $this->configure();
    }

    /**
     * Setup the configuration for FailedJobs.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/failedjobs.php', 'failedjobs'
        );
    }

}
