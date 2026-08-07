# Architecture and extension points

## Package boundaries

- `Nocs\\Cabin\\Providers\\CabinServiceProvider` merges config, binds the manager, registers the command, publishes assets, and loads migrations.
- `Nocs\\Cabin\\Support\\CabinManager` owns session-aware locking, key normalization, connection selection, expiration cleanup, and guard detection.
- `Nocs\\Cabin\\Models\\CabinLock` is the Eloquent persistence model for `cabin_lock`.
- `Nocs\\Cabin\\Commands\\CabinLockRemoveExpired` exposes scheduled cleanup.
- `Nocs\\Cabin\\Support\\Facades\\Cabin` provides the facade accessor for the `cabin` binding.
- `helpers/helpers.php` defines the `cabin()` helper.

## Extension points

The supported customization points are configuration and Laravel integration:

1. Set `models.user` to a custom user model implementing the relationship expected by Eloquent.
2. Select a database connection with `cabin()->connection(...)` before performing operations.
3. Publish and own migrations when the host application needs explicit migration control.
4. Add application-level wrappers, policies, UI, and scheduled tasks around the public manager API.

There is no documented interface for replacing the manager, model, key hashing, expiration strategy, or guard lookup. Replacing those internals requires an application-specific binding/subclass strategy and should be treated as a compatibility-sensitive change.

## Key and session semantics

`createKey($key)` applies `Str::slug($key)` and then MD5. Different inputs that slug to the same value therefore share a lock. The manager caches the session ID at construction; call `refreshSessionID()` if a long-lived process changes session context.

`isLocked()` deliberately excludes the current session. This allows a session to reacquire/refresh its own logical lock while reporting contention only to other sessions. `lockedBy()` returns the stored owner for the normalized key without filtering by session.

## Concurrency and failure behavior

The manager first performs a read check, then saves the row. If the host schema has a unique constraint, recognized unique-constraint errors provide a final contention guard and return `false`; unrelated database errors are rethrown. The current published migration creates ordinary indexes, not a unique key constraint, while the test fixture does add a unique key. Applications requiring database-enforced race protection should review this explicitly and still use transactions for the protected write itself.

The inspected code's initial migration creates the columns and the later migration adds indexes. Confirm the package migration history against the installed version before applying custom schema changes.

## Integration cautions

- The lock table stores a numeric `locked_by` column in the published migration, while current tests also cover string/UUID user IDs through a custom model. Verify the target database's column behavior before relying on long string identifiers in production.
- The package uses the current Laravel session and auth services. Queue workers and other non-session contexts need an explicit application integration decision; this package does not define a distributed worker identity.
- Locks are advisory coordination, not authorization or ownership transfer.

**To be documented by NOCS:** the supported policy for anonymous sessions, queue/CLI contexts, and lock takeover/administrative release.
