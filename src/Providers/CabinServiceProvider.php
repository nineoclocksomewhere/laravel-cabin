<?php

namespace Nocs\Cabin\Providers;

use Nocs\Cabin\Support\CabinManager;
use Nocs\Cabin\Commands\CabinLockRemoveExpired;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

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
        $this->mergeConfigFrom(__DIR__.'/../../config/cabin.php', 'cabin');

        $this->app->bind('cabin', function ($app) {
            return new CabinManager($app);
        });

        $this->commands([
            CabinLockRemoveExpired::class,
        ]);

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
                __DIR__.'/../../database/migrations/2026_06_12_000000_create_cabin_lock_table.php' => $this->getMigrationFileName('create_cabin_lock_table.php'),
            ], 'cabin-migrations');

            // Export the config
            $this->publishes([
                __DIR__.'/../../config/cabin.php' => config_path('cabin.php'),
            ], 'config');
        }

        if ((bool) config('cabin.load_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
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