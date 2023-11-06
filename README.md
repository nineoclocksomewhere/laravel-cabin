# laravel-cabin
Key-based resource locking by session ID

## Installation
You install this package using composer:

```bash
composer require nocs/laravel-cabin
```

The config allows you to set the expiration time. The default expiration time is 10 minutes.
Publish the config using:

```bash
php artisan vendor:publish --provider="Nocs\Cabin\Providers\CabinServiceProvider" --tag="config"
```

Publish the database migrations using and run the migration:

```bash
php artisan vendor:publish --provider="Nocs\Cabin\Providers\CabinServiceProvider" --tag="migrations"
```

## Usage

You can freely determine the key. For example your model followed by the id of item. A lock is defined by the key and the session id. If available the user id will be stored as extra info.

```php
  cabin()->lock('blog_12');
```

```php
  cabin()->unlock('blog_12');
```

```php
  cabin()->removeExpired();
```

You can reset the locak time by pinging

```php
  cabin()->ping('blog_12');
```

You can use the following command to remove expired locks

```bash
php artisan cabin:remove-expired
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
