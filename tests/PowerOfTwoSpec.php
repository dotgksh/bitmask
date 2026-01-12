<?php

use Gksh\Bitmask\Enums\Size;
use Gksh\Bitmask\Values\PowerOfTwo;

use function Gksh\Bitmask\Support\isPowerOfTwo;

$powerOfTwoGenerator = function () {
    for ($i = 0; $i <= 32; $i++) {
        yield 2 ** $i;
    }
};

$notPowerOfTwoGenerator = function (int $min, int $max, int $amount = 500) {
    $i = 0;
    $int = $min;

    while ($i < $amount && $int <= $max) {
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
    ->with($notPowerOfTwoGenerator(min: 0, max: Size::UInt8->maxValue()))
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 256 to 65535', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: Size::UInt8->maxValue() + 1, max: Size::UInt16->maxValue()))
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 65536 to 16777215', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: Size::UInt16->maxValue() + 1, max: Size::UInt24->maxValue()))
    ->throws(InvalidArgumentException::class);

it('is not power of two w/ random int from 16777216 to 4294967295', function (int $value) {
    new PowerOfTwo($value);
})
    ->with($notPowerOfTwoGenerator(min: Size::UInt24->maxValue() + 1, max: Size::UInt32->maxValue()))
    ->throws(InvalidArgumentException::class);
