# Changelog
All notable changes to `bitmask` will be documented in this file.

## [Unreleased]

## [1.0.0] - 2025-01-11
> Major refactor: Unified API with enum support

### Changed
- Consolidated all bitmask variants into single immutable `Bitmask` class
- Factory methods: `make()`, `tiny()`, `small()`, `medium()` for different sizes
- Direct enum support in all methods (BackedEnum and UnitEnum)
- PHP 8.2+ required

### Added
- `Size` enum with computed `maxValue()`
- `maskValue()` helper for enum-to-int conversion
- UnitEnum support (position-inferred values)

### Removed
- `BaseBitmask`, `TinyBitmask`, `SmallBitmask`, `MediumBitmask` classes
- `MaxValue` enum (replaced by `Size::maxValue()`)
- `PowerOfTwo` value object (replaced by `isPowerOfTwo()` helper)

## [0.0.1] - 2024-05-03
> Initial release

### Added
- Initial bitmask implementation
- Playground examples

[unreleased]: https://github.com/dotgksh/bitmask/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/dotgksh/bitmask/compare/v0.0.1...v1.0.0
[0.0.1]: https://github.com/dotgksh/bitmask/releases/tag/v0.0.1
