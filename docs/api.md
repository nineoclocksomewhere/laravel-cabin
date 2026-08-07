# Public API

The package exposes the `cabin()` helper, the `cabin` container binding, and the `Nocs\\Cabin\\Support\\Facades\\Cabin` facade. The helper resolves the manager from the Laravel container.

## CabinManager methods

| Method | Return | Behavior |
|---|---|---|
| `connection(string $connection)` | `CabinManager` | Selects the database connection used by subsequent operations; chainable. |
| `refreshSessionID()` | `void` | Refreshes the manager's cached session ID. Use when the authenticated/session context changes during a long-lived process or test. |
| `lock(string $key)` | `bool` | Removes expired rows, checks whether another session owns the normalized key, then creates or updates this session's row. Returns `false` on contention. |
| `unlock(string $key)` | `true` | Deletes the row matching the normalized key and current session ID. It does not delete another session's lock. |
| `isLocked(string $key)` | `bool` | Returns whether a non-expired row for the normalized key belongs to another session. |
| `lockedBy(string $key)` | `int|string|false` | Returns the stored `locked_by` value for the key, or `false` when no row remains or the stored owner is null. It is not limited to another session. |
| `removeExpired()` | `true` | Deletes rows whose `locked_at` is older than `expiration_time` on the selected connection. |
| `ping(string $key)` | `bool` | Refreshes `locked_at` only for this session's row; returns `false` if this session does not hold the key. |
| `createKey(string $key)` | `string` | Normalizes with `Str::slug()` and returns the MD5 digest used in storage. |

The implementation also forwards facade/container calls through Laravel's normal facade mechanism. Unknown manager methods fail with a bad-method-call exception; there is no extension callback API in the package.

## Lock lifecycle

1. The manager captures the current session ID when constructed.
2. `lock($key)` normalizes the key, removes expired rows, and checks other sessions.
3. A new row stores the session ID, current timestamp, authenticated user ID if available, and the detected guard.
4. A recognized unique-key contention raised by the database is converted to `false` rather than leaked as an exception. The current published migration does not itself create a unique index, so applications requiring database-enforced race protection should review/add that constraint.
5. `ping($key)` keeps the current session's row alive.
6. `unlock($key)` removes only the current session's row.

## Example: guarded acquisition

```php
$key = 'article_'.$article->getKey();

if (! cabin()->lock($key)) {
    abort(409, 'This resource is being edited by another session.');
}

try {
    // Perform the application operation.
} finally {
    cabin()->unlock($key);
}
```

A lock must not replace authorization or transaction-level consistency controls.
