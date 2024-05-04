<p align="center">
    <img src="https://banners.beyondco.de/gksh%2Fbitmask.png?theme=light&packageManager=composer+require&packageName=gksh%2Fbitmask&pattern=architect&style=style_1&description=A+bitmask+value+object+for+PHP&md=1&showWatermark=0&fontSize=200px&images=flag&widths=100&heights=100" alt="Bitmask banner">
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

## 🧪 Usage
Streamline flag handling by encoding boolean options into simple integers through bitmasking.

> Please see [ide.php](./playground/ide.php) for full example and [playground](./playground) for more.

```php
enum Panel: int
{
    case Project = 1;
    case Terminal = 2;
    case SourceControl = 4;
    case Extensions = 8;
}

class Panels extends TinyBitmask
{
    public function isVisible(Panel $panel): bool
    {
        return $this->has($panel->value);
    }

    public function togglePanel(Panel $panel): Panels
    {
        return $this->toggle($panel->value);
    }
}

class Ide
{
    public Panels $panels;

    public function togglePanel(Panel $panel): self
    {
        $this->panels->togglePanel($panel);

        return $this;
    }
}

$ide = (new Ide())
    ->togglePanel(Panel::Project)
    ->togglePanel(Panel::Terminal);

$ide->panels->isVisible(Panel::Terminal); // true
$ide->panels->isVisible(Panel::Extensions); // false
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
