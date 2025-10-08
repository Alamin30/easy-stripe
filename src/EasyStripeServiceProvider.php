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
    }
}
