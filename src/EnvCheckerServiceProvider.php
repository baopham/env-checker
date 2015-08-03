<?php

namespace BaoPham\EnvChecker;

use Illuminate\Support\ServiceProvider;

/**
 * Class EnvCheckerServiceProvider
 */
class EnvCheckerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/envchecker.php' => config_path('envchecker.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(EnvCheckerCommand::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [EnvCheckerCommand::class];
    }

}
