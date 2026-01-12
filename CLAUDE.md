# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

A PHP bitmask value object library (`gksh/bitmask`) that provides type-safe bitwise operations with direct enum support. Single immutable `Bitmask` class with factory methods for different sizes (8, 16, 24, 32-bit).

## Commands

```bash
composer install          # Install dependencies
composer test             # Run all checks (refactor, lint, types, unit tests)
composer test:unit        # Run Pest unit tests only
composer test:types       # Run PHPStan static analysis
composer test:lint        # Check code style with Pint
composer test:refacto     # Check Rector refactoring rules
composer lint             # Fix code style with Pint
composer refacto          # Apply Rector refactoring

# Run a single test file
./vendor/bin/pest tests/BitmaskSpec.php

# Run tests matching a pattern
./vendor/bin/pest --filter="sets flag"
```

## Architecture

### Core Class

`Bitmask` - Immutable (`final readonly`) value object with factory methods:
- `Bitmask::make($value, $size)` - 32-bit default
- `Bitmask::tiny($value)` - 8-bit (max 255)
- `Bitmask::small($value)` - 16-bit (max 65,535)
- `Bitmask::medium($value)` - 24-bit (max 16,777,215)

Operations: `set()`, `unset()`, `toggle()`, `has()`, `value()`, `size()`, `equals()`

### Key Components

- `Enums/Size` - Defines bit-widths (UInt8, UInt16, UInt24, UInt32) with computed `maxValue()`
- `Contracts/ValueObject` - Interface requiring `value()` and `equals()` methods
- `Support/helpers.php` - Contains `isPowerOfTwo()` and `maskValue()` helper functions

### Design Pattern

All flags must be powers of two (1, 2, 4, 8, 16...). The `isPowerOfTwo()` helper enforces this constraint. Bitmasks are immutable - operations return new instances.

Accepts `int`, `BackedEnum`, or `UnitEnum` in all methods:
- `BackedEnum`: Uses the enum's integer value directly
- `UnitEnum`: Infers value from position (1 << position)

## Testing

Uses Pest PHP with data providers. Tests use `Flag` (BackedEnum) and `UnitFlag` (UnitEnum) defined in `tests/`. Common patterns:

```php
// Parameterized tests with enum cases
it('sets flag', function (Flag $flag) {
    // ...
})->with(Flag::cases());

// Testing boundary conditions
it('throws if out of bounds', function (Size $size, int $value) {
    // ...
})->with([...])->throws(InvalidArgumentException::class);
```

## Code Quality

- PHPStan at max level with strict types
- Pint for PSR-12 code style
- Rector for automated refactoring
- PHP 8.2+ required
