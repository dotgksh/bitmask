<?php

namespace Gksh\Bitmask\Tests;

enum Flag: int
{
    case A = 0b00000001;
    case B = 0b00000010;
    case C = 0b00000100;
    case D = 0b00001000;
    case E = 0b00010000;
    case F = 0b00100000;
    case G = 0b01000000;
    case H = 0b10000000;
}
