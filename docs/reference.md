# Reference

## Entry points

| Entry point | Value |
| --- | --- |
| Helper | `cabin()` resolves `app('cabin')`. |
| Container binding | `cabin`, bound to a `Nocs\\Cabin\\Support\\CabinManager`. |
| Facade | `Nocs\\Cabin\\Support\\Facades\\Cabin`, backed by `cabin`. |
| Provider | `Nocs\\Cabin\\Providers\\CabinServiceProvider`. |

## `CabinManager`

| Method | Return | Behavior |
| --- | --- | --- |
| `connection(string $connection)` | `CabinManager` | Selects the Eloquent connection for subsequent operations. |
| `refreshSessionID()` | `void` | Re-reads the current session ID into the manager. |
| `lock(string $key)` | `bool` | Cleans expired rows, checks other sessions, then creates or updates this session's row. Recognized unique-constraint contention returns `false`. |
| `unlock(string $key)` | `true` | Deletes this session's row for the normalized key; it does not delete another session's row. |
| `isLocked(string $key)` | `bool` | Returns whether another session has a non-expired row for the key. |
| `lockedBy(string $key)` | `int|string|false` | Returns the stored owner ID, or `false` if no owner value is available. |
| `removeExpired()` | `true` | Deletes rows with `locked_at` at or before now minus `expiration_time`. |
| `ping(string $key)` | `bool` | Updates `locked_at` for this session's row, or returns `false` if absent. |
| `createKey(string $key)` | `string` | Returns `md5(Str::slug($key))`. |

Unknown manager methods are rejected through Laravel's bad-method-call path.

## `CabinLock`

`Nocs\\Cabin\\Models\\CabinLock` maps to `cabin_lock`, uses `locked_at` as its update timestamp, and defines:

- `user(): BelongsTo`, using `config('cabin.models.user')` and `locked_by`;
- `isExpired(): bool`, based on `locked_at + expiration_time`.

The migration does not create a foreign key for `locked_by`; anonymous locks and custom user models are supported.

## Configuration

| Key | Default | Meaning |
| --- | --- | --- |
| `expiration_time` | `600` seconds | Lifetime used by cleanup and `CabinLock::isExpired()`. |
| `load_migrations` | `true` | Whether the provider loads package migrations automatically. |
| `models.user` | `App\\Models\\User` | User model used by the `user()` relation. |

## Console command

`php artisan cabin:remove-expired` calls `cabin()->removeExpired()` and prints `Expired locks have been removed`.
