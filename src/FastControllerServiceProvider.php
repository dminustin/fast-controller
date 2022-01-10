<?php

declare(strict_types=1);

namespace FastController;

use FastController\Console\FastControllerGenerateCommand;
use Illuminate\Support\ServiceProvider;

class FastControllerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $viewPath = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($viewPath, 'fast-controller');

        // Publish a config file
        $configPath = __DIR__.'/../config/fast-controller.php';
        $this->publishes([
            $configPath => config_path('fast-controller.php'),
        ], 'config');

        //Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => config('fast-controller.views_path'),
        ], 'views');

        //Register commands
        $this->commands([FastControllerGenerateCommand::class]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__.'/../config/fast-controller.php';
        $this->mergeConfigFrom($configPath, 'fast-controller');

        $this->app->singleton('command.fast-controller:create', function ($app) {
            return $app->make(FastControllerGenerateCommand::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.fast-controller.generate',
        ];
    }
}
