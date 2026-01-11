<?php

declare(strict_types=1);

namespace Gksh\Bitmask\Support;

use BackedEnum;
use InvalidArgumentException;
use UnitEnum;

function isPowerOfTwo(int $value): bool
{
    return $value && ! ($value & ($value - 1));
}

function maskValue(int|UnitEnum|BackedEnum $flag): int
{
    if ($flag instanceof BackedEnum) {
        if (! is_int($flag->value)) {
            throw new InvalidArgumentException('BackedEnum must have integer backing value');
        }

        return $flag->value;
    }

    if ($flag instanceof UnitEnum) {
        $position = array_search($flag, $flag::cases(), true);

        return 1 << $position;
    }

    return $flag;
}
