# Dependencies and compatibility

## Runtime dependencies

| Dependency | Constraint | Purpose |
|---|---:|---|
| PHP | `^8.0` | Package runtime |
| `illuminate/support` | `~7|~8|~9|~10|~11|~12|~13` | Laravel support APIs |

Laravel 13 requires PHP 8.3 or newer. The package currently declares `minimum-stability: dev` and a Composer repository at `https://packages.nineoclocksomewhe.re`.

## Development dependencies

| Dependency | Constraint | Purpose |
|---|---:|---|
| `orchestra/testbench` | `^10.11 || ^11.0` | Laravel package integration tests |

## Verification

The package manifest was validated with `composer validate --strict`. After restoring the lockfile dependencies with `composer install`, the test suite passed locally with PHP 8.3.30 and also passed the GitHub Actions PHP 8.3, 8.4, and 8.5 matrix for the documented source revision. See [`testing.md`](testing.md) for exact evidence.
