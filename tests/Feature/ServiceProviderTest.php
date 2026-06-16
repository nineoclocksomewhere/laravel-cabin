<?php

declare(strict_types=1);

namespace Nocs\Cabin\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Nocs\Cabin\Providers\CabinServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

final class ServiceProviderTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            CabinServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
    }

    private function publishSourcePath(): string
    {
        return dirname(__DIR__, 2) . '/src/Providers/../../database/migrations/2026_06_12_000000_create_cabin_lock_table.php';
    }

    public function testItPublishesPackageMigrations(): void
    {
        $paths = ServiceProvider::pathsToPublish(
            CabinServiceProvider::class,
            'cabin-migrations',
        );

        self::assertArrayHasKey($this->publishSourcePath(), $paths);
        self::assertTrue(str_starts_with(
            $paths[$this->publishSourcePath()],
            database_path('migrations'),
        ));
    }

    public function testItLoadsPackageMigrationsByDefault(): void
    {
        self::assertTrue(Schema::hasTable('cabin_lock'));
    }
}
