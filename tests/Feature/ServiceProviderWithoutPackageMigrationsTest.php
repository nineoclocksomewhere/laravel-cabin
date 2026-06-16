<?php

declare(strict_types=1);

namespace Nocs\Cabin\Tests\Feature;

use Nocs\Cabin\Providers\CabinServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

final class ServiceProviderWithoutPackageMigrationsTest extends BaseTestCase
{
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
        $app['config']->set('cabin.load_migrations', false);
    }

    public function testItCanSkipLoadingPackageMigrations(): void
    {
        self::assertNotContains(
            dirname(__DIR__, 2) . '/database/migrations',
            app('migrator')->paths(),
        );
    }
}
