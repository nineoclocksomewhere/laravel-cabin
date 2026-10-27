# Architecture

## Bootstrapping

`CabinServiceProvider`:

1. merges `config/cabin.php` under the `cabin` key;
2. binds `cabin` to a new `CabinManager`;
3. registers `CabinLockRemoveExpired`;
4. in console, exposes `config` and `cabin-migrations` publish tags; and
5. loads `database/migrations` unless `cabin.load_migrations` is false.

Composer package discovery points Laravel at this provider.

## Lock lifecycle

The manager captures the default database connection and session ID when constructed. For a lock operation it slug-normalizes the caller's key, hashes it with MD5, removes expired records, and checks for rows owned by a different session. A new row records:

- normalized hashed `key`;
- `session_id`;
- `locked_at`;
- authenticated `locked_by`, when available; and
- the detected non-Sanctum `locked_guard`, or the default auth driver.

`ping()` updates only the current session's row. `unlock()` deletes only that row. `isLocked()` and `lockedBy()` remove expired rows before reading.

## Persistence model

The migrations create `cabin_lock` with `id`, `key`, `session_id`, nullable `locked_by`, nullable `locked_guard`, and `locked_at`. A follow-up migration adds indexes on `key`, `(key, session_id)`, and `locked_at`.

The published migrations do **not** add a unique constraint. `lock()` defensively converts recognized database unique-constraint errors into `false`, but applications that require database-enforced race protection must review their schema and concurrency requirements.

## Expiration and cleanup

Expiration is calculated as `locked_at + config('cabin.expiration_time', 600)`. Reads perform opportunistic cleanup. The console command provides explicit cleanup; no scheduler, queue worker, event, or HTTP layer is registered by the package.

## Extension boundaries

The package is intentionally a manager/model/provider layer. It does not define resource-specific policies, takeover behavior, UI, authorization, API routes, events, jobs, webhooks, or notifications. Host applications own those concerns.
