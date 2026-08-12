# Getting started

## 1. Install

From a Laravel application:

```bash
composer require nocs/laravel-cabin
```

Composer package discovery registers `Nocs\\Cabin\\Providers\\CabinServiceProvider` automatically through the `extra.laravel.providers` manifest entry. If discovery is disabled, register that provider manually.

## 2. Run migrations

By default, the provider loads the package migrations automatically. Run:

```bash
php artisan migrate
```

This creates `cabin_lock` and its indexes. To publish the migrations into the host application instead:

```bash
php artisan vendor:publish --provider="Nocs\\Cabin\\Providers\\CabinServiceProvider" --tag="cabin-migrations"
```

Then set `load_migrations` to `false` and run the published migrations:

```php
// config/cabin.php
'load_migrations' => false,
```

The publisher reuses an existing host migration with the same filename when one is present.

## 3. Publish configuration (optional)

```bash
php artisan vendor:publish --provider="Nocs\\Cabin\\Providers\\CabinServiceProvider" --tag="config"
```

The defaults are:

```php
return [
    'expiration_time' => 10 * 60,
    'load_migrations' => true,
    'models' => [
        'user' => App\\Models\\User::class,
    ],
];
```

## 4. Acquire and release a lock

Choose a stable application key and use `lock()` before protected work. Always release it in a `finally` block; use `ping()` for work that can outlive the expiration interval. See [Usage](usage.md) for the complete lifecycle.

## 5. Schedule cleanup

Reads clean up expired rows for the selected connection, but abandoned rows can remain when a key is never read again. Schedule the command in the host application's scheduler or invoke it from another maintenance process:

```bash
php artisan cabin:remove-expired
```

The command reports `Expired locks have been removed` after calling `cabin()->removeExpired()`.
