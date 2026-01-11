<p align="center">
    <img src="https://banners.beyondco.de/gksh%2Fbitmask.png?theme=light&packageManager=composer+require&packageName=gksh%2Fbitmask&pattern=wiggle&style=style_1&description=A+bitmask+value+object+for+PHP&md=1&showWatermark=0&fontSize=200px&images=flag&widths=100&heights=100" alt="Bitmask banner">
</p>

# gksh/bitmask
A simple way to use bitmask and bitwise operations in PHP.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gksh/bitmask.svg)](https://packagist.org/packages/gksh/bitmask)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/dotgksh/bitmask/tests.yml?branch=main)](https://github.com/dotgksh/bitmask/actions?query=workflow%3Atests+branch%3Amain)
[![License](https://img.shields.io/packagist/l/gksh/bitmask.svg)](https://github.com/dotgksh/bitmask/blob/main/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/gksh/bitmask.svg)](https://packagist.org/packages/gksh/bitmask)

## Installation
> **Requires [PHP 8.2+](https://php.net/releases/)**
```bash
composer require gksh/bitmask
```

## Usage
Streamline flag handling by encoding boolean options into simple integers through bitmasking.

> Please see [ide.php](./playground/ide.php) for full example and [playground](./playground) for more.

```php
use Gksh\Bitmask\Bitmask;

enum Panel: int
{
    case Project = 1;
    case Terminal = 2;
    case SourceControl = 4;
    case Extensions = 8;
}

class Ide
{
    public Bitmask $panels;

    public function __construct()
    {
        $this->panels = Bitmask::tiny(); // 8-bit
    }

    public function togglePanel(Panel $panel): self
    {
        $this->panels = $this->panels->toggle($panel);

        return $this;
    }
}

$ide = (new Ide())
    ->togglePanel(Panel::Project)
    ->togglePanel(Panel::Terminal);

$ide->panels->has(Panel::Terminal); // true
$ide->panels->has(Panel::Extensions); // false
```

### Features

- **Immutable**: Operations return new instances, original unchanged
- **Enum support**: Pass `BackedEnum` directly — no `->value` extraction needed
- **Size variants**: `tiny()` (8-bit), `small()` (16-bit), `medium()` (24-bit), `make()` (32-bit default)

### Factory Methods

```php
Bitmask::make()         // 32-bit (default)
Bitmask::tiny()         // 8-bit, for TINYINT columns
Bitmask::small()        // 16-bit, for SMALLINT columns
Bitmask::medium()       // 24-bit, for MEDIUMINT columns
```

### Operations

```php
$mask = Bitmask::tiny()
    ->set(Flag::A)      // Set a flag
    ->unset(Flag::B)    // Unset a flag
    ->toggle(Flag::C);  // Toggle a flag

$mask->has(Flag::A);    // Check if flag is set
$mask->value();         // Get integer value
$mask->size();          // Get Size enum
```

## Testing
```bash
composer test
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
- [Gustavo Karkow](https://github.com/karkowg)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
