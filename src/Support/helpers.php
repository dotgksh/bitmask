<?php

if (! function_exists('isPowerOfTwo')) {
    function isPowerOfTwo(int $value): bool
    {
        return $value && ! ($value & ($value - 1));
    }
}
