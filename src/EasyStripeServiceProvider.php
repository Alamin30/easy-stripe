<?php

namespace Alamin\EasyStripe;

use Alamin\EasyStripe\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;

class EasyStripeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/easy-stripe.php', 'easy-stripe');
    }

    public function boot()
    {
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/easy-stripe.php' => config_path('easy-stripe.php'),
        ], 'easy-stripe-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'easy-stripe-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/easy-stripe'),
        ], 'easy-stripe-views');

    }
}
