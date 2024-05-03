<?php

declare(strict_types=1);

namespace Gksh\Bitmask\Values;

use Gksh\Bitmask\Contracts\ValueObject;
use InvalidArgumentException;

readonly class PowerOfTwo implements ValueObject
{
    public function __construct(private int $value)
    {
        if (! isPowerOfTwo($value)) {
            throw new InvalidArgumentException("Value $value is not a power of two");
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(PowerOfTwo|ValueObject $other): bool
    {
        return $this->value === $other->value();
    }
}
