<?php

declare(strict_types=1);

namespace Gksh\Bitmask\Enums;

enum Size: int
{
    case UInt8 = 8;
    case UInt16 = 16;
    case UInt24 = 24;
    case UInt32 = 32;

    public function maxValue(): int
    {
        return (1 << $this->value) - 1;
    }
}
