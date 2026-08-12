# Laravel Cabin

Laravel Cabin provides session-scoped, key-based locks for shared resources. Use it when an application needs to coordinate concurrent editing or processing of a logical resource such as `article_123`.

## Documentation

- [Documentation hub](docs/README.md)
- [Getting started](docs/getting-started.md)
- [Usage guide](docs/usage.md)
- [API reference](docs/reference.md)
- [Architecture and data model](docs/architecture.md)
- [Compatibility and integration surface](docs/compatibility.md)
- [Testing and verification](docs/testing.md)

## Quick start

```bash
composer require nocs/laravel-cabin
php artisan migrate
```

```php
$key = 'article_'.$article->getKey();

if (! cabin()->lock($key)) {
    abort(409, 'This resource is being edited by another session.');
}

try {
    // Work with the resource.
} finally {
    cabin()->unlock($key);
}
```

The package identifies ownership by the current session ID. It stores the authenticated user ID and detected guard when available, but a lock is a coordination mechanism—not an authorization check.

## Requirements

- PHP `^8.0`
- Illuminate Support `~7|~8|~9|~10|~11|~12|~13`
- Laravel applications should use the Illuminate version compatible with their framework version.

## Cleanup

Expired locks are removed opportunistically by lock-state reads and can be removed explicitly:

```bash
php artisan cabin:remove-expired
```

## License

Laravel Cabin is open-sourced under the [MIT license](LICENSE).
