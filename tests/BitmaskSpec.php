<?php

use Gksh\Bitmask\Bitmask;
use Gksh\Bitmask\MediumBitmask;
use Gksh\Bitmask\SmallBitmask;
use Gksh\Bitmask\Tests\Flag;
use Gksh\Bitmask\TinyBitmask;

test('default value is zero', function () {
    expect(Bitmask::make()->value())->toBe(0);
});

it('makes instance with flag value', function (Flag $flag) {
    $mask = Bitmask::make($flag->value);

    expect($mask->value())->toBe($flag->value);
})->with(Flag::cases());

it('sets flag', function (Flag $flag) {
    $mask = Bitmask::make()->set($flag->value);

    expect($mask->value())->toBe($flag->value)
        ->and($mask->has($flag->value))->toBeTrue();
})->with(Flag::cases());

it('sets multiple flags', function () {
    $mask = Bitmask::make()
        ->set($a = Flag::A->value)
        ->set($b = Flag::B->value)
        ->set($c = Flag::C->value);

    expect($mask->value())->toBe($a | $b | $c)
        ->and($mask->has($a))->toBeTrue()
        ->and($mask->has($b))->toBeTrue()
        ->and($mask->has($c))->toBeTrue();
});

it('toggles flag', function (Flag $flag) {
    $mask = Bitmask::make()->toggle($flag->value);

    expect($mask->has($flag->value))->toBeTrue();

    $mask->toggle($flag->value);

    expect($mask->has($flag->value))->toBeFalse();
})->with(Flag::cases());

it('unsets flag', function (Flag $flag) {
    $mask = Bitmask::make()->set($flag->value);

    expect($mask->value())->toBe($flag->value)
        ->and($mask->has($flag->value))->toBeTrue();

    $mask->unset($flag->value);

    expect($mask->value())->toBe(0)
        ->and($mask->has($flag->value))->toBeFalse();
})->with(Flag::cases());

it('has flag', function (Flag $flag) {
    $mask = Bitmask::make($flag->value);

    expect($mask->has($flag->value))->toBeTrue();
})->with(Flag::cases());

it('does not have flag', function () {
    $mask = Bitmask::make(Flag::A->value);

    expect($mask->has(Flag::A->value))->toBeTrue()
        ->and($mask->has(Flag::B->value))->toBeFalse();
});

test('equality', function () {
    expect(Bitmask::make()->equals(new Bitmask()))->toBeTrue()
        ->and(Bitmask::make()->equals(Bitmask::make()))->toBeTrue();

    $mask1 = Bitmask::make(Flag::A->value);
    $mask2 = Bitmask::make(Flag::A->value);

    expect($mask1->equals($mask2))->toBeTrue()
        ->and($mask2->equals($mask1))->toBeTrue();

    $mask1 = Bitmask::make(Flag::A->value | Flag::B->value | Flag::C->value);

    $mask2 = Bitmask::make()
        ->set(Flag::A->value)
        ->set(Flag::B->value)
        ->set(Flag::C->value);

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
        ->set(Flag::A->value)
        ->set(Flag::B->value)
        ->set(Flag::C->value);

    $mask2 = Bitmask::make()
        ->set(Flag::A->value)
        ->set(Flag::C->value);

    expect($mask1->equals($mask2))->toBeFalse()
        ->and($mask2->equals($mask1))->toBeFalse();
});

it('can instantiate with max integer value', function (string $class, int $value) {
    match ($class) {
        TinyBitmask::class => TinyBitmask::make($value),
        SmallBitmask::class => SmallBitmask::make($value),
        MediumBitmask::class => MediumBitmask::make($value),
        default => Bitmask::make($value),
    };
})
    ->with([
        [TinyBitmask::class, 0b11111111],
        [SmallBitmask::class, 0b1111111111111111],
        [MediumBitmask::class, 0b111111111111111111111111],
        [Bitmask::class, 0b11111111111111111111111111111111],
    ])
    ->throwsNoExceptions();

it('throws if instantiating with out of bounds integer', function (string $class, int $value) {
    match ($class) {
        TinyBitmask::class => TinyBitmask::make($value),
        SmallBitmask::class => SmallBitmask::make($value),
        MediumBitmask::class => MediumBitmask::make($value),
        default => Bitmask::make($value),
    };
})
    ->with([
        [TinyBitmask::class, -1],
        [TinyBitmask::class, 0b100000000],
        [SmallBitmask::class, -1],
        [SmallBitmask::class, 0b10000000000000000],
        [MediumBitmask::class, -1],
        [MediumBitmask::class, 0b1000000000000000000000000],
        [Bitmask::class, -1],
        [Bitmask::class, 0b100000000000000000000000000000000],
        [Bitmask::class, PHP_INT_MAX],
    ])
    ->throws(InvalidArgumentException::class);

it('throws if setting out of bounds integer flag', function (string $class, int $value) {
    $mask = match ($class) {
        TinyBitmask::class => TinyBitmask::make(),
        SmallBitmask::class => SmallBitmask::make(),
        MediumBitmask::class => MediumBitmask::make(),
        default => Bitmask::make(),
    };

    $mask->set($value);
})
    ->with([
        [TinyBitmask::class, -1],
        [TinyBitmask::class, 0b100000000],
        [SmallBitmask::class, -1],
        [SmallBitmask::class, 0b10000000000000000],
        [MediumBitmask::class, -1],
        [MediumBitmask::class, 0b1000000000000000000000000],
        [Bitmask::class, -1],
        [Bitmask::class, 0b100000000000000000000000000000000],
        [Bitmask::class, PHP_INT_MAX],
    ])
    ->throws(InvalidArgumentException::class);
