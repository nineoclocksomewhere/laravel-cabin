# laravel-cabin

Key-based resource locking by session ID

This fork supports Laravel 12 and Laravel 13. Laravel 13 requires PHP 8.3+ so Composer can resolve the newer Testbench stack.

## Installation

You install this package using composer:

```bash
composer require nocs/laravel-cabin
```

The config allows you to set the expiration time and the user model. The default expiration time is 10 minutes. The default user model is App\Models\User

```php
'models' => [
    'user' => Your\Custom\Models\User::class,
],
```

Publish the config using:

```bash
php artisan vendor:publish --provider="Nocs\Cabin\Providers\CabinServiceProvider" --tag="config"
```

Publish the database migrations using and run the migration:

```bash
php artisan vendor:publish --provider="Nocs\Cabin\Providers\CabinServiceProvider" --tag="migrations"
```

## Usage

You can freely determine the key. For example your modelname followed by the id of the item. A lock is defined by the key and the users session id. If available, the user id will be stored as extra info.

```php
  cabin()->lock('blog_12');
```

```php
  cabin()->unlock('blog_12');
```

```php
  cabin()->removeExpired();
```

You can reset the locked time by pinging

```php
  cabin()->ping('blog_12');
```

You can use the following command to remove expired locks

```bash
php artisan cabin:remove-expired
```

You can get the id of the user that initiated the lock

```php
  cabin()->lockedBy();
```

You can manually set a DB connection

```php
  cabin()->connection('sqlB')->lock('blog_12');
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
