<?php

namespace Alamin\EasyStripe\Services;

use Exception;
use Illuminate\Support\Facades\File;

class StripeInstaller
{
    /**
     * @throws Exception
     */
    public function publishAll(): void
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->publishViews();
        $this->publishRoutes();
    }

    public function publishConfig(): void
    {
        \Artisan::call('vendor:publish', ['--tag' => 'stripe-config', '--force' => true]);
    }

    public function publishMigrations(): void
    {
        \Artisan::call('vendor:publish', ['--tag' => 'stripe-migrations', '--force' => true]);
    }

    public function publishViews(): void
    {
        // Publish views directly to resources/views/easy-stripe instead of vendor folder
        $sourceViewsPath = __DIR__ . '/../../resources/views';
        $destinationViewsPath = resource_path('views/easy-stripe');

        if (File::exists($sourceViewsPath)) {
            // Remove existing views if they exist
            if (File::exists($destinationViewsPath)) {
                File::deleteDirectory($destinationViewsPath);
            }

            // Copy views to new location
            File::copyDirectory($sourceViewsPath, $destinationViewsPath);
        }
    }

    /**
     * Copy controller and model from package to app directory.
     * @throws Exception
     */
    public function copyFiles(): void
    {
        $this->publishStubToApp(
            __DIR__ . '/../stubs/StripePaymentController.stub',
            app_path('Http/Controllers/StripePaymentController.php'),
            [
                'DummyNamespace' => 'App\\Http\\Controllers',
                'DummyModelNamespace' => 'App\\Models\\StripePayment',
                'DummyClass' => 'StripePaymentController',
                'DummyModelClass' => 'StripePayment',
                'DummyViewPath' => 'easy-stripe.payment',
            ]
        );

        $this->publishStubToApp(
            __DIR__ . '/../stubs/StripePayment.stub',
            app_path('Models/StripePayment.php'),
            [
                'DummyNamespace' => 'App\\Models',
                'DummyClass' => 'StripePayment',
            ]
        );
    }

    /**
     * Helper to publish a stub file to a real PHP class
     * @throws Exception
     */
    protected function publishStubToApp($stubPath, $destinationPath, array $replacements = []): void
    {
        if (!File::exists($stubPath)) {
            throw new Exception("Stub file not found: {$stubPath}");
        }

        $stubContent = File::get($stubPath);

        // Replace all placeholders
        foreach ($replacements as $search => $replace) {
            $stubContent = str_replace($search, $replace, $stubContent);
        }

        // Ensure directory exists
        File::ensureDirectoryExists(dirname($destinationPath));

        // Save as .php file
        File::put($destinationPath, $stubContent);
    }

    /**
     * @throws Exception
     */
    public function publishRoutes(): void
    {
        $webRoutesPath = base_path('routes/web.php');
        $stubPath = __DIR__ . '/../stubs/routes.stub';

        if (!file_exists($stubPath)) {
            throw new \Exception('Route stub not found!');
        }

        $routesContent = file_get_contents($stubPath);

        // Replace placeholders
        $routesContent = str_replace(
            ['DummyNamespace', 'DummyClass'],
            ['App\\Http\\Controllers', 'StripePaymentController'],
            $routesContent
        );

        if (file_exists($webRoutesPath)) {
            $existingContent = file_get_contents($webRoutesPath);

            // Check if routes already exist
            if (strpos($existingContent, 'StripePaymentController') !== false) {
                return;
            }

            // Append to existing web.php
            file_put_contents($webRoutesPath, "\n\n" . $routesContent, FILE_APPEND);
        } else {
            // Create new web.php if it doesn't exist (rare)
            file_put_contents($webRoutesPath, "<?php\n\n" . $routesContent);
        }
    }

}
