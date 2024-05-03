<?php

use Gksh\Bitmask\Enums\MaxValue;
use Gksh\Bitmask\Values\PowerOfTwo;

$powerOfTwoGenerator = function () {
    for ($i = 0; $i <= 32; $i++) {
        yield 2 ** $i;
    }
};

$notPowerOfTwoGenerator = function (int $min, int $max, int $amount = 100) {
    $i = 0;

    while ($i < $amount) {
        $int = random_int($min, $max);

        if (isPowerOfTwo($int)) {
            continue;
        }

        yield $int;

        $i++;
    }
};

it('is power of two', function (int $value) {
    expect((new PowerOfTwo($value))->value())->toBe($value);
})->with($powerOfTwoGenerator());

test('zero is not power of two', function () {
    new PowerOfTwo(0);
})->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 0 to 255', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: 0, max: MaxValue::UInt8->value))
    ->repeat(5)
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 256 to 65535', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: MaxValue::UInt8->value + 1, max: MaxValue::UInt16->value))
    ->repeat(5)
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 65536 to 16777215', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: MaxValue::UInt16->value + 1, max: MaxValue::UInt24->value))
    ->repeat(5)
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 16777216 to 4294967295', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: MaxValue::UInt24->value + 1, max: MaxValue::UInt32->value))
    ->repeat(5)
    ->throws(InvalidArgumentException::class);
