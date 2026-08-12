# Usage

The package exposes the `cabin()` helper, the `cabin` container binding, and the `Nocs\\Cabin\\Support\\Facades\\Cabin` facade.

## Acquire a lock

```php
$key = 'article_'.$article->getKey();

if (! cabin()->lock($key)) {
    abort(409, 'This resource is being edited by another session.');
}

try {
    // Perform the protected operation.
} finally {
    cabin()->unlock($key);
}
```

`lock()` returns `true` when the current session can create/update its row and `false` when another session currently owns the normalized key. The current session is not blocked by its own row.

## Inspect ownership

```php
if (cabin()->isLocked($key)) {
    $ownerId = cabin()->lockedBy($key); // int|string, or false when absent
}
```

`isLocked()` only reports a non-expired row belonging to another session. `lockedBy()` returns the stored `locked_by` value for the key regardless of session; it returns `false` when there is no row or the stored owner is null.

## Keep a lock alive

```php
cabin()->ping($key); // true when this session's row was refreshed; false otherwise
```

Call `ping()` before `expiration_time` elapses for long-running editing flows. It only updates a row matching both the normalized key and the manager's current session ID.

## Release and clean up

```php
cabin()->unlock($key);       // always returns true
cabin()->removeExpired();   // deletes old rows on the selected connection
```

`unlock()` deletes only the current session's row. State reads also call `removeExpired()` before querying.

## Select a database connection

The manager is chainable:

```php
cabin()->connection('sqlB')->lock($key);
cabin()->connection('sqlB')->isLocked($key);
```

The selected connection remains on that manager instance and is used by later operations. The default is `config('database.default')` when the manager is constructed.

## Refresh session identity

The manager caches the session ID in its constructor. In a long-lived process, or after changing the session context in a test, refresh it explicitly:

```php
cabin()->refreshSessionID();
```

## Key normalization

`createKey($key)` applies `Str::slug()` and stores the resulting value as an MD5 digest. Distinct input strings that slug to the same value therefore share a lock. Choose keys deliberately and consistently.

## Security boundary

A lock does not grant permission and does not replace authorization, validation, or transaction isolation. The package has no HTTP endpoints, events, jobs, webhooks, or takeover policy.
