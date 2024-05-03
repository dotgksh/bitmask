<?php

namespace Gksh\Bitmask\Contracts;

interface ValueObject
{
    public function value(): mixed;

    public function equals(self $other): bool;
}
