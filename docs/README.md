# Laravel Cabin documentation

Laravel Cabin provides session-scoped, key-based locking for shared resources. An application can use it to prevent two sessions from editing the same logical resource at the same time.

These documents describe the implementation in the inspected revision recorded in [`source-context.md`](source-context.md). They do not infer product policy about which resources should be locked or who may override a lock.

## Contents

- [Getting started](getting-started.md)
- [Public API](api.md)
- [Configuration and persistence](configuration.md)
- [Architecture and extension points](architecture.md)
- [Dependencies](dependencies.md)
- [Testing and verification](testing.md)
- [Source context](source-context.md)

## Important behavior

- A lock is identified by a normalized key and the current session ID.
- `lock()` returns `false` when another session already owns the key. If the host schema has a uniqueness constraint, recognized database contention is also returned as `false`.
- Lock timestamps expire after `cabin.expiration_time` seconds. Read operations clean up expired rows.
- The current session is not considered blocked by its own lock.
- The package stores the authenticated user ID and detected guard when a lock is first created, when authentication is available.

## Security and operational notes

- Do not copy `.env` values, credentials, or production database details into documentation.
- A lock is an application coordination mechanism, not an authorization check. Protected actions still need their normal authorization and validation.
- If automatic migration loading is disabled, the host application owns publication and execution of both package migrations.
- **To be documented by NOCS:** whether a given application should permit lock takeover, what user-facing behavior should be shown for a locked resource, and how lock cleanup is scheduled in production.

## Integration surface

- HTTP API: none identified
- Bruno collection: not applicable
- Domain events/listeners: none identified
- Queue jobs: none identified
- Webhooks: none identified
- Console command: `cabin:remove-expired`
- Scheduled behavior: the package exposes the cleanup command; scheduling it is the host application's responsibility.
