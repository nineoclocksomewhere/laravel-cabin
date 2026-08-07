# Getting started

## Requirements and installation

`composer.json` declares PHP `^8.0` and `illuminate/support` `~7|~8|~9|~10|~11|~12|~13`. Laravel 13 requires PHP 8.3 or newer. Install the package in a Laravel application:

```bash
composer require nocs/laravel-cabin
```

The package registers `Nocs\\Cabin\\Providers\\CabinServiceProvider` through Laravel package discovery. If package discovery is disabled, register that provider explicitly.

## Default setup

By default, the service provider:

1. Merges the package `config/cabin.php` configuration.
2. Binds `cabin` to `Nocs\\Cabin\\Support\\CabinManager`.
3. Registers `cabin:remove-expired`.
4. Loads the package migrations automatically.

The default setup needs no published files. To publish configuration:

```bash
php artisan vendor:publish --provider="Nocs\\Cabin\\Providers\\CabinServiceProvider" --tag="config"
```

To make migrations part of the host application's migration tree instead:

```bash
php artisan vendor:publish --provider="Nocs\\Cabin\\Providers\\CabinServiceProvider" --tag="cabin-migrations"
```

If the host owns those migrations, set `load_migrations` to `false` to avoid loading them twice.

## Basic usage

Use a stable logical key, normally composed from a resource type and identifier:

```php
$key = 'article_'.$article->id;

if (cabin()->lock($key)) {
    // This session acquired the lock.
}

if (cabin()->isLocked($key)) {
    // Another session currently holds the lock.
}

cabin()->ping($key);   // refresh this session's lock timestamp
cabin()->unlock($key); // release this session's lock
```

`lock()` and `unlock()` operate against the configured default database connection. Select a connection before an operation when the lock table is on another connection:

```php
cabin()->connection('sqlB')->lock($key);
```

For a console cleanup, run:

```bash
php artisan cabin:remove-expired
```

The package does not define application-specific authorization, UI, or takeover behavior. Add those policies in the consuming application.
