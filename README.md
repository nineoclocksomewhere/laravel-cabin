# laravel-cabin
Key-based resource locking

## Installation
You install this package using composer:

```bash
composer require nocs/laravel-cabin
```

Publish the database migrations using and run the migration:

```bash
php artisan vendor:publish --provider="Nocs\Cabin\Providers\CabinServiceProvider" --tag="migrations"
```

## Testing

For new packages use command:
```sh
composer require --dev "orchestra/testbench=^6.0"
```

But for this project the composer.json is already up-to-date, so run:
```sh
composer install --dev
```

To test, run:
```sh
composer test
```
