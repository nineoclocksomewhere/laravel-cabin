# Configuration and persistence

## Configuration

The published `config/cabin.php` contains:

| Key | Default | Meaning |
|---|---:|---|
| `expiration_time` | `600` | Lock lifetime in seconds since `locked_at`. |
| `load_migrations` | `true` | Whether the service provider calls `loadMigrationsFrom()` for package migrations. |
| `models.user` | `App\\Models\\User` | Model class used by `CabinLock::user()` to resolve the lock owner. |

Example:

```php
return [
    'expiration_time' => 10 * 60,
    'load_migrations' => true,
    'models' => [
        'user' => App\\Models\\User::class,
    ],
];
```

The package reads configuration at runtime. Changing `expiration_time` changes the expiration threshold used by cleanup and `CabinLock::isExpired()`; it does not rewrite existing timestamps.

## Database schema

The package migrations create `cabin_lock` with:

| Column | Definition | Use |
|---|---|---|
| `id` | auto-incrementing ID | Row identity |
| `key` | string | MD5 hash from `createKey()` |
| `session_id` | string | Session that owns the lock |
| `locked_by` | nullable unsigned big integer | Authenticated user key when available |
| `locked_guard` | nullable string | Detected authentication guard |
| `locked_at` | datetime | Last acquisition or ping time |

The follow-up migration adds ordinary indexes named `cabin_lock_key_index`, `cabin_lock_key_session_id_index`, and `cabin_lock_locked_at_index`; it does not add a unique constraint. The host database connection must support the schema used by the migrations and by Eloquent. The repository test fixture separately adds a unique `key` constraint, so do not assume that constraint exists after installing the package migrations.

`Nocs\\Cabin\\Models\\CabinLock` maps to this table, uses `locked_at` as its update timestamp, and exposes a `user()` relationship to the configured model. The migration does not add a foreign key to `locked_by`; this permits configurable user models and nullable/anonymous locks.

## Migration ownership

Default mode is package-managed migration loading. To own the migrations in the host application, publish them with the `cabin-migrations` tag and set:

```php
'load_migrations' => false,
```

The service provider avoids overwriting an existing host migration with the same migration filename when publishing.

## Cleanup

Reads call `removeExpired()` before checking lock state. Applications should also run `php artisan cabin:remove-expired` on a schedule or another suitable maintenance path so abandoned rows are removed even when no request reads that key. The command prints `Expired locks have been removed` after cleanup.
