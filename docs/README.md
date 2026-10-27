# Laravel Cabin documentation

Laravel Cabin is a small Laravel package for session-scoped, key-based resource locking. This documentation is derived from the package source, Composer manifest, migrations, tests, and GitHub Actions workflow in the repository.

## Contents

- [Getting started](getting-started.md) — install, migrate, and configure a host application.
- [Usage](usage.md) — acquire, inspect, refresh, release, and clean up locks.
- [Reference](reference.md) — helper, binding, facade, manager methods, model, and command.
- [Architecture](architecture.md) — service-provider bootstrapping, storage model, and lifecycle.
- [Compatibility](compatibility.md) — PHP/Laravel constraints and integration boundaries.
- [Testing](testing.md) — local commands, test coverage, and CI.

## Integration inventory

- HTTP routes/controllers/API: **none in this package**.
- Bruno collection: **none**.
- Domain events/listeners: **none**.
- Queue jobs: **none**.
- Webhooks: **none**.
- Console command: `cabin:remove-expired`.
- Scheduling: no scheduler registration; scheduling cleanup is the host application's responsibility.

## Important boundaries

Locks are application coordination, not authorization. Continue to apply authorization, validation, and database transaction rules around protected work. The package does not provide lock takeover policy, UI responses, HTTP endpoints, or distributed scheduling policy.
