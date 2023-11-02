<?php

namespace Nocs\Cabin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Nocs\Cabin\Support\CabinManager;

/**
 * CabinServiceProvider class
 */
class CabinServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('cabin', function ($app) {
            return new CabinManager($app);
        });

    }

    /**
     * [boot description]
     * @return [type] [description]
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            // Export the migration
            $this->publishes([
                __DIR__.'/../database/migrations/create_cabin_lock_table.php.stub' => $this->getMigrationFileName('create_cabin_lock_table.php'),
                
            ], 'migrations');
        }

    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

}