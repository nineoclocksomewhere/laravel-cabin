# Compatibility

## Declared Composer constraints

From `composer.json`:

- PHP: `^8.0`.
- `illuminate/support`: `~7|~8|~9|~10|~11|~12|~13`.
- Development test harness: `orchestra/testbench` `^10.11 || ^11.0`.

The package advertises Laravel 7 through Laravel 13 through its Illuminate Support constraint. The repository CI currently tests PHP 8.3, 8.4, and 8.5; it does not provide a matrix for every Laravel major. Resolve the complete Illuminate/Testbench combination against the target application's Laravel version before deployment.

## Runtime integrations

The provider uses Laravel's service container, configuration, console command registration, migration loader/publisher, sessions, authentication, Eloquent, and database connections. The database driver must support the schema and Eloquent operations used by the migrations.

The package has no HTTP routes or controllers, Bruno collection, domain events/listeners, queue jobs, webhooks, or built-in scheduler registration.

## Database notes

The package migration stores `locked_by` as an unsigned big integer, although the model relationship is configurable and tests cover string/UUID user IDs. If the application uses a string user key, review the host schema and model/database compatibility before production use.

The package migrations add ordinary indexes but no uniqueness constraint. Add or manage concurrency constraints only after reviewing the application's lock semantics and database platform.

## Operational responsibility

The host application must choose stable keys, enforce authorization separately, schedule cleanup if desired, and decide how contention is presented to users. Production behavior should be verified against the application's session, auth, database, and deployment topology.
