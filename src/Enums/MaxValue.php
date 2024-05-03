<?php

namespace Gksh\Bitmask\Enums;

enum MaxValue: int
{
    case UInt8 = 0b11111111; // 8 bits = 255 (decimal)
    case UInt16 = 0b1111111111111111; // 16 bits = 65535 (decimal)
    case UInt24 = 0b111111111111111111111111; // 24 bits = 16777215 (decimal)
    case UInt32 = 0b11111111111111111111111111111111; // 32 bits = 4294967295 (decimal)
}
