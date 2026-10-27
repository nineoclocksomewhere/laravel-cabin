# Testing

## Local verification

Install dependencies and run the repository test script:

```bash
composer validate --strict
composer install --prefer-dist --no-interaction --no-progress
composer test
```

`composer test` runs `vendor/bin/phpunit --testdox`. PHPUnit discovers feature tests under `tests/Feature` and uses the repository's SQLite/Testbench setup.

## What the tests cover

`CabinFeatureTest` verifies:

- locking and identifying an authenticated owner;
- contention between two session IDs;
- expiration after time advances;
- keeping a lock alive with `ping()`;
- separate database connections;
- unlock behavior; and
- custom user models with string/UUID IDs.

`ServiceProviderTest` verifies migration publication, default migration loading, and the three package indexes. `ServiceProviderWithoutPackageMigrationsTest` verifies that `load_migrations=false` prevents package migration paths from loading.

## CI

`.github/workflows/php.yml` runs on pull requests and pushes to `master` with PHP 8.3, 8.4, and 8.5. Each matrix job:

1. validates `composer.json` and `composer.lock` with `composer validate --strict`;
2. installs dependencies with Composer; and
3. runs `composer run-script test`.

The workflow enables `mbstring`, `dom`, `sqlite3`, and `pdo_sqlite`, and passes the optional `COMPOSER_AUTH` secret to dependency installation without documenting or exposing its value.

## Verification scope

The repository tests exercise the package's SQLite/Testbench behavior. They do not prove application-specific authorization, scheduler configuration, production database contention, or HTTP/UI behavior; those are outside this package and must be verified by consuming applications.
