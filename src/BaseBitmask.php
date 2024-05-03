<?php

declare(strict_types=1);

namespace Gksh\Bitmask;

use Gksh\Bitmask\Contracts\ValueObject;
use Gksh\Bitmask\Enums\MaxValue;
use Gksh\Bitmask\Values\PowerOfTwo;
use InvalidArgumentException;

abstract class BaseBitmask implements ValueObject
{
    protected int $value = 0;

    private readonly int $maxValue;

    final public function __construct(int $value = 0)
    {
        $this->maxValue = $this->maxValue();

        if ($value < 0 || $value > $this->maxValue) {
            throw new InvalidArgumentException("Invalid unsigned integer $value");
        }

        $this->value = $value;
    }

    protected function maxValue(): int
    {
        return MaxValue::UInt32->value;
    }

    private function assertPowerOfTwo(int $value): void
    {
        new PowerOfTwo($value);
    }

    public function set(int $flag): static
    {
        $this->assertPowerOfTwo($flag);

        $value = $this->value | $flag;

        if ($value < 0 || $value > $this->maxValue) {
            throw new InvalidArgumentException("Invalid unsigned integer $value");
        }

        $this->value = $value;

        return $this;
    }

    public function unset(int $flag): static
    {
        $this->assertPowerOfTwo($flag);

        $this->value &= (~$flag);

        return $this;
    }

    public function toggle(int $flag): static
    {
        $this->assertPowerOfTwo($flag);

        $this->value ^= $flag;

        return $this;
    }

    public function has(int $flag): bool
    {
        $this->assertPowerOfTwo($flag);

        return ($this->value & $flag) === $flag;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(BaseBitmask|ValueObject $other): bool
    {
        return $this->value === $other->value();
    }
}
