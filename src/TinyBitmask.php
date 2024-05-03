<?php

declare(strict_types=1);

namespace Gksh\Bitmask;

use Gksh\Bitmask\Enums\MaxValue;

class TinyBitmask extends BaseBitmask
{
    public static function make(int $value = 0): static
    {
        return new static($value);
    }

    protected function maxValue(): int
    {
        return MaxValue::UInt8->value;
    }
}