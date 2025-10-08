<?php

namespace Alamin\EasyStripe\Console;

use Illuminate\Console\Command;
use Alamin\EasyStripe\Services\StripeInstaller;

class InstallCommand extends Command
{
    protected $signature = 'easy-stripe:install';
    protected $description = 'Install Stripe Integration package';

    /**
     * @throws \Exception
     */
    public function handle(StripeInstaller $installer): void
    {
        $installer->publishAll();

        $this->info('Easy Stripe installation completed successfully!');
    }
}
