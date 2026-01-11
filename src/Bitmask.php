<?php

declare(strict_types=1);

namespace Gksh\Bitmask;

use BackedEnum;
use Gksh\Bitmask\Contracts\ValueObject;
use Gksh\Bitmask\Enums\Size;
use InvalidArgumentException;
use UnitEnum;

use function Gksh\Bitmask\Support\isPowerOfTwo;
use function Gksh\Bitmask\Support\maskValue;

final readonly class Bitmask implements ValueObject
{
    private int $value;

    private int $maxValue;

    public function __construct(
        int|UnitEnum|BackedEnum $value = 0,
        private Size $size = Size::UInt32,
    ) {
        $this->maxValue = $this->size->maxValue();
        $this->value = maskValue($value);

        if ($this->value < 0 || $this->value > $this->maxValue) {
            throw new InvalidArgumentException("Invalid unsigned integer {$this->value}");
        }
    }

    public static function make(int|UnitEnum|BackedEnum $value = 0, Size $size = Size::UInt32): self
    {
        return new self($value, $size);
    }

    public static function tiny(int|UnitEnum|BackedEnum $value = 0): self
    {
        return new self($value, Size::UInt8);
    }

    public static function small(int|UnitEnum|BackedEnum $value = 0): self
    {
        return new self($value, Size::UInt16);
    }

    public static function medium(int|UnitEnum|BackedEnum $value = 0): self
    {
        return new self($value, Size::UInt24);
    }

    public function set(int|UnitEnum|BackedEnum $flag): self
    {
        $flagValue = $this->flagValue($flag);
        $newValue = $this->value | $flagValue;

        if ($newValue > $this->maxValue) {
            throw new InvalidArgumentException("Value $newValue exceeds max {$this->maxValue}");
        }

        return new self($newValue, $this->size);
    }

    public function unset(int|UnitEnum|BackedEnum $flag): self
    {
        $flagValue = $this->flagValue($flag);

        return new self($this->value & (~$flagValue), $this->size);
    }

    public function toggle(int|UnitEnum|BackedEnum $flag): self
    {
        $flagValue = $this->flagValue($flag);
        $newValue = $this->value ^ $flagValue;

        if ($newValue > $this->maxValue) {
            throw new InvalidArgumentException("Value $newValue exceeds max {$this->maxValue}");
        }

        return new self($newValue, $this->size);
    }

    public function has(int|UnitEnum|BackedEnum $flag): bool
    {
        $flagValue = $this->flagValue($flag);

        return ($this->value & $flagValue) === $flagValue;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function size(): Size
    {
        return $this->size;
    }

    public function equals(ValueObject $other): bool
    {
        return $this->value === $other->value();
    }

    private function flagValue(int|UnitEnum|BackedEnum $flag): int
    {
        $value = maskValue($flag);

        if (! isPowerOfTwo($value)) {
            throw new InvalidArgumentException("Value $value is not a power of two");
        }

        return $value;
    }
}
