<?php

use Gksh\Bitmask\Bitmask;
use Gksh\Bitmask\Enums\Size;
use Gksh\Bitmask\Tests\Flag;
use Gksh\Bitmask\Tests\UnitFlag;

test('default value is zero', function () {
    expect(Bitmask::make()->value())->toBe(0);
});

test('default size is UInt32', function () {
    expect(Bitmask::make()->size())->toBe(Size::UInt32);
});

it('makes instance with flag value', function (Flag $flag) {
    $mask = Bitmask::make($flag->value);

    expect($mask->value())->toBe($flag->value);
})->with(Flag::cases());

it('makes instance with BackedEnum', function (Flag $flag) {
    $mask = Bitmask::tiny($flag);

    expect($mask->value())->toBe($flag->value);
})->with(Flag::cases());

it('makes instance with UnitEnum', function (UnitFlag $flag) {
    $mask = Bitmask::tiny($flag);
    $expectedValue = 1 << array_search($flag, UnitFlag::cases(), true);

    expect($mask->value())->toBe($expectedValue);
})->with(UnitFlag::cases());

it('sets flag with int', function (Flag $flag) {
    $mask = Bitmask::make()->set($flag->value);

    expect($mask->value())->toBe($flag->value)
        ->and($mask->has($flag->value))->toBeTrue();
})->with(Flag::cases());

it('sets flag with BackedEnum', function (Flag $flag) {
    $mask = Bitmask::make()->set($flag);

    expect($mask->value())->toBe($flag->value)
        ->and($mask->has($flag))->toBeTrue();
})->with(Flag::cases());

it('sets flag with UnitEnum', function (UnitFlag $flag) {
    $mask = Bitmask::make()->set($flag);
    $expectedValue = 1 << array_search($flag, UnitFlag::cases(), true);

    expect($mask->value())->toBe($expectedValue)
        ->and($mask->has($flag))->toBeTrue();
})->with(UnitFlag::cases());

it('sets multiple flags', function () {
    $mask = Bitmask::make()
        ->set(Flag::A)
        ->set(Flag::B)
        ->set(Flag::C);

    expect($mask->value())->toBe(Flag::A->value | Flag::B->value | Flag::C->value)
        ->and($mask->has(Flag::A))->toBeTrue()
        ->and($mask->has(Flag::B))->toBeTrue()
        ->and($mask->has(Flag::C))->toBeTrue();
});

it('is immutable - set returns new instance', function () {
    $original = Bitmask::make();
    $modified = $original->set(Flag::A);

    expect($original->value())->toBe(0)
        ->and($modified->value())->toBe(Flag::A->value)
        ->and($original)->not->toBe($modified);
});

it('toggles flag', function (Flag $flag) {
    $mask = Bitmask::make()->toggle($flag);

    expect($mask->has($flag))->toBeTrue();

    $mask = $mask->toggle($flag);

    expect($mask->has($flag))->toBeFalse();
})->with(Flag::cases());

it('is immutable - toggle returns new instance', function () {
    $original = Bitmask::make();
    $modified = $original->toggle(Flag::A);

    expect($original->value())->toBe(0)
        ->and($modified->value())->toBe(Flag::A->value);
});

it('unsets flag', function (Flag $flag) {
    $mask = Bitmask::make()->set($flag);

    expect($mask->value())->toBe($flag->value)
        ->and($mask->has($flag))->toBeTrue();

    $mask = $mask->unset($flag);

    expect($mask->value())->toBe(0)
        ->and($mask->has($flag))->toBeFalse();
})->with(Flag::cases());

it('is immutable - unset returns new instance', function () {
    $original = Bitmask::make()->set(Flag::A);
    $modified = $original->unset(Flag::A);

    expect($original->value())->toBe(Flag::A->value)
        ->and($modified->value())->toBe(0);
});

it('has flag', function (Flag $flag) {
    $mask = Bitmask::make($flag->value);

    expect($mask->has($flag))->toBeTrue();
})->with(Flag::cases());

it('does not have flag', function () {
    $mask = Bitmask::make(Flag::A->value);

    expect($mask->has(Flag::A))->toBeTrue()
        ->and($mask->has(Flag::B))->toBeFalse();
});

test('equality', function () {
    expect(Bitmask::make()->equals(new Bitmask))->toBeTrue()
        ->and(Bitmask::make()->equals(Bitmask::make()))->toBeTrue();

    $mask1 = Bitmask::make(Flag::A->value);
    $mask2 = Bitmask::make(Flag::A->value);

    expect($mask1->equals($mask2))->toBeTrue()
        ->and($mask2->equals($mask1))->toBeTrue();

    $mask1 = Bitmask::make(Flag::A->value | Flag::B->value | Flag::C->value);

    $mask2 = Bitmask::make()
        ->set(Flag::A)
        ->set(Flag::B)
        ->set(Flag::C);

    expect($mask1->equals($mask2))->toBeTrue()
        ->and($mask2->equals($mask1))->toBeTrue();
});

test('different instances are not equal', function () {
    expect(Bitmask::make()->equals(Bitmask::make(Flag::A->value)))->toBeFalse();

    $mask1 = Bitmask::make(Flag::A->value);
    $mask2 = Bitmask::make(Flag::B->value);

    expect($mask1->equals($mask2))->toBeFalse()
        ->and($mask2->equals($mask1))->toBeFalse();

    $mask1 = Bitmask::make()
        ->set(Flag::A)
        ->set(Flag::B)
        ->set(Flag::C);

    $mask2 = Bitmask::make()
        ->set(Flag::A)
        ->set(Flag::C);

    expect($mask1->equals($mask2))->toBeFalse()
        ->and($mask2->equals($mask1))->toBeFalse();
});

// Factory method tests
test('tiny factory creates 8-bit bitmask', function () {
    $mask = Bitmask::tiny();
    expect($mask->size())->toBe(Size::UInt8);
});

test('small factory creates 16-bit bitmask', function () {
    $mask = Bitmask::small();
    expect($mask->size())->toBe(Size::UInt16);
});

test('medium factory creates 24-bit bitmask', function () {
    $mask = Bitmask::medium();
    expect($mask->size())->toBe(Size::UInt24);
});

test('make factory with size parameter', function () {
    expect(Bitmask::make(0, Size::UInt8)->size())->toBe(Size::UInt8)
        ->and(Bitmask::make(0, Size::UInt16)->size())->toBe(Size::UInt16)
        ->and(Bitmask::make(0, Size::UInt24)->size())->toBe(Size::UInt24)
        ->and(Bitmask::make(0, Size::UInt32)->size())->toBe(Size::UInt32);
});

// Size boundary tests
it('can instantiate with max integer value', function (Size $size, int $value) {
    Bitmask::make($value, $size);
})
    ->with([
        [Size::UInt8, 0b11111111],
        [Size::UInt16, 0b1111111111111111],
        [Size::UInt24, 0b111111111111111111111111],
        [Size::UInt32, 0b11111111111111111111111111111111],
    ])
    ->throwsNoExceptions();

it('throws if instantiating with out of bounds integer', function (Size $size, int $value) {
    Bitmask::make($value, $size);
})
    ->with([
        [Size::UInt8, -1],
        [Size::UInt8, 0b100000000],
        [Size::UInt16, -1],
        [Size::UInt16, 0b10000000000000000],
        [Size::UInt24, -1],
        [Size::UInt24, 0b1000000000000000000000000],
        [Size::UInt32, -1],
        [Size::UInt32, 0b100000000000000000000000000000000],
        [Size::UInt32, PHP_INT_MAX],
    ])
    ->throws(InvalidArgumentException::class);

it('throws if setting out of bounds integer flag', function (Size $size, int $value) {
    Bitmask::make(0, $size)->set($value);
})
    ->with([
        [Size::UInt8, 0b100000000],
        [Size::UInt16, 0b10000000000000000],
        [Size::UInt24, 0b1000000000000000000000000],
        [Size::UInt32, 0b100000000000000000000000000000000],
    ])
    ->throws(InvalidArgumentException::class);

// String-backed enum rejection test
it('throws for string-backed enum', function () {
    enum StringFlag: string
    {
        case X = 'x';
    }

    Bitmask::make()->set(StringFlag::X);
})->throws(InvalidArgumentException::class);
