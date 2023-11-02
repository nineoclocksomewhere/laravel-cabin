<?php

namespace Nocs\Package\Providers;

use Illuminate\Support\ServiceProvider;
use Nocs\Package\Support\PackageManager;

/**
 * PackageServiceProvider class
 */
class PackageServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('package', function ($app) {
            return new PackageManager($app);
        });

    }

    /**
     * [boot description]
     * @return [type] [description]
     */
    public function boot()
    {

        // ...

        if ($this->app->runningInConsole()) {

            // ...

        }

        // ...

    }

}