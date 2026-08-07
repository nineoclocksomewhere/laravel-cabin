# Testing and verification

## Repository verification

| Check | Expected | Observed | Status |
|---|---|---|---|
| Release branch | `master` | Local checkout `master`, tracking `origin/master` | pass |
| Inspected revision | Current approved package source | `642b1abd4afd7e2b7bfca88d3fe8e9fa2ba532fe` (`v2.2.5`) | pass |
| Source inspection | Docs match implementation | README, Composer manifest, config, service provider, manager, model, migrations, command, and feature tests inspected | pass |
| Composer metadata | Valid package manifest | `composer validate --strict` passed locally | pass |
| PHPUnit | Package tests pass | After `composer install`, `composer run-script test` passed locally: 9 tests, 38 assertions | pass |
| Composer audit | Locked dependencies have no known advisories | `composer audit --locked` and `composer audit --no-dev` passed with no advisories | pass |
| GitHub Actions | Configured matrix passes for source revision | Run `28016276315`, PHP 8.3/8.4/8.5, success | pass |

## Test coverage in the repository

`tests/Feature/CabinFeatureTest.php` covers:

- locking and identifying the authenticated owner;
- expiration after elapsed time;
- refreshing a lock with `ping()`;
- selecting separate database connections;
- single-session contention behavior; and
- string/UUID user IDs with a custom configured user model.

`tests/Feature/ServiceProviderTest.php` covers package migration publication, default migration loading, and the expected indexes. `tests/Feature/ServiceProviderWithoutPackageMigrationsTest.php` verifies that `load_migrations=false` prevents package migration paths from loading.

## Reproduction commands

From the repository root:

```bash
composer install
composer test
```

The Composer script runs `vendor/bin/phpunit --testdox`.

## Verification limitations

The local verification used PHP 8.3.30. Dependencies were restored with `composer install`; `composer validate --strict` and `composer run-script test` then passed. CI run `28016276315` also passed the repository's PHP 8.3, 8.4, and 8.5 matrix for revision `642b1abd4afd7e2b7bfca88d3fe8e9fa2ba532fe`. Production database behavior, scheduled cleanup, and application-specific authorization still require consuming-application verification.
