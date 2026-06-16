<?php

namespace Nocs\Cabin\Tests;

use Nocs\Cabin\Providers\CabinServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;


class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {

        parent::setUp();

        $this->runMigrations();
        
        config()->set('database.default', 'sqliteB');
        $this->runMigrations();
        
        config()->set('database.default', 'sqlite');

    }

    private function runMigrations() {
        include_once __DIR__.'/Database/migrations/create_articles_table.php.stub';
        (new \CreateArticlesTable())->up();

        include_once __DIR__.'/Database/migrations/create_users_table.php.stub';
        (new \CreateUsersTable())->up();

        \Illuminate\Support\Facades\Schema::dropIfExists('cabin_lock');
        $migration = require __DIR__.'/../database/migrations/2026_06_12_000000_create_cabin_lock_table.php';
        $migration->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            CabinServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('database.connections.sqliteB', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

    }

}