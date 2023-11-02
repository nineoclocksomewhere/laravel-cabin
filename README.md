# laravel-package
Laravel package starter template

## Installation

- Rename all 'Package' occurrences in namespaces to the name of this package.
- Also rename the **Package**ServiceProvider accordingly.

## Testing

For new packages use command:
```sh
composer require --dev "orchestra/testbench=^6.0"
```

But for this project the composer.json is already up-to-date, so run:
```sh
composer install --dev
```

To test, run:
```sh
composer test
```
