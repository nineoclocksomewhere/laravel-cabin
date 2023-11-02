<?php

namespace Nocs\Package\Tests;

use Nocs\Package\Providers\PackageServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    public function setUp(): void
    {

        parent::setUp();

        // additional setup

    }

    protected function getPackageProviders($app)
    {
        return [
            PackageServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        // perform environment setup

    }

}